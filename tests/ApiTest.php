<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp;

class CalculatorTest extends TestCase
{
    private $http;

    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost:80/']);
    }

    public function tearDown(): void {
        $this->http = null;
    }

    private function callAndCheck(string $uri) {
        $response = $this->http->request('GET', $uri);
        $this->assertEquals(200, $response->getStatusCode());

        $headers = $response->getHeaders();

        // Check content type
        $contentType = $headers["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        return [
            'headers' => $headers,
            'body' => json_decode($response->getBody())
        ];
    }

    public function testGetProduct()
    {
        $serviceActions = 'SERVICE-0.25-20, SERVICE-0.10-12, SERVICE-SODA-6, SERVICE-WATER-12, SERVICE-JUICE-7';
        $expectedServiceActions = 'SERVICE-20x0.25, SERVICE-12x0.10, SERVICE-6xSODA, SERVICE-12xWATER, SERVICE-7xJUICE, ';
        $actionsBaseUri = "/api/vendingmachine/actions/";

        // Case 1: Buy Soda with exact change
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, 0.25, 0.25, GET-SODA")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "SODA", $jBody->result);

        // Case 2: Start adding money, but user ask for return coin
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 0.10, 0.10, RETURN-COIN")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "0.10, 0.10", $jBody->result);

        // Case 3: Buy Water without exact change
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-WATER")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "WATER, 0.25, 0.10", $jBody->result);

        // Case 4: Buy soda without enough money
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-SODA")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "NO-ENOUGH-MONEY", $jBody->result);

        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-SODA")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "NO-ENOUGH-MONEY", $jBody->result);

        // The service only set available change with 0.25 coins
        $serviceActions = 'SERVICE-0.25-20, SERVICE-SODA-6, SERVICE-WATER-12, SERVICE-JUICE-7';
        $expectedServiceActions = 'SERVICE-20x0.25, SERVICE-6xSODA, SERVICE-12xWATER, SERVICE-7xJUICE, ';

        // Case 5: buy a product but ,due to the returning available coin amounts are not the same that inserting
        //          available coin amounts, there is not possible to return to the user all the returning amount
        //          In this example the user pay with an euro coin
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-WATER")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "WATER, 0.25, NO-MORE-AVAILABLE-MONEY", $jBody->result);

        // Case 6: buy a product but it is not possible to return all the return amount to the client
        //         In this example the client enter an 1 euro coin, select buying water (costs 0.65e), the returning
        //         amount is 0.35e but there are only 0.25 coins available, so only the machine returns a 0.25 coin
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-WATER")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "WATER, 0.25, NO-MORE-AVAILABLE-MONEY", $jBody->result);

        // The service only set available change with only 4 0.25 coins
        $serviceActions = 'SERVICE-0.25-4, SERVICE-SODA-6, SERVICE-WATER-12';
        $expectedServiceActions = 'SERVICE-4x0.25, SERVICE-6xSODA, SERVICE-12xWATER, ';

        // Case 7: buy a product but, besides there is money inside the vending machine for return all the amount to te client,
        //          due to the returning available coin amounts are not the same that inserting
        //          available coin amounts, there is not possible to return to the user all the returning amount
        //          In this example the user insert 3 euros coins, select to by soda (1.5e). There are moneys inside the
        //          machine for pay (1e + 0.25e + 0.25e) but due to 1e is not available to return, the machine online can return
        //          all the 0.25e coins that has (4 0.25e coins)
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1,1,1, GET-SODA")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "SODA, 0.25, 0.25, 0.25, 0.25, NO-MORE-AVAILABLE-MONEY", $jBody->result);

        // Case 8: the product trying to buy is not available
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-JUICE")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "NO-PRODUCT-AVAILABLE", $jBody->result);

        // Case 9: case where
        $serviceActions = 'SERVICE-0.25-20, SERVICE-0.10-12, SERVICE-0.05-15, SERVICE-SODA-6, SERVICE-WATER-12, SERVICE-JUICE-7';
        $expectedServiceActions = 'SERVICE-20x0.25, SERVICE-12x0.10, SERVICE-15x0.05, SERVICE-6xSODA, SERVICE-12xWATER, SERVICE-7xJUICE, ';
        $actionsBaseUri = "/api/vendingmachine/actions/";

        // Case 10: we enter some type coins (1e and 0.10e) and the machine returns other type (0.25 and 0.05 coins)
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1,0.10,0.10, GET-WATER")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "WATER, 0.25, 0.25, 0.05", $jBody->result);
    }
}
