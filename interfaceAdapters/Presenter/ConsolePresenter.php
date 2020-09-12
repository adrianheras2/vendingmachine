<?php

namespace InterfaceAdapters\Presenter;


use Domain\Entity\Money;
use Symfony\Component\HttpFoundation\JsonResponse;

class ConsolePresenter
{
    public function present(string $result): object
    {
        return $this->json([
            'status' => 'OK',
            'result' => $result
        ]);
    }

    public function presentResult(array $result): object
    {
        $result = implode(', ', $result);

        return $this->json([
            'status' => 'OK',
            'result' => $result
        ]);
    }

    public function presentError(string $msg, array $data = []): object
    {
        $result = [
            'status' => 'error',
            'result' => $msg,

        ];

        if (!empty($data)){
            $result[ 'data'] = $data;
        }

        return $this->json($result);
    }

    /**
     * Presents validation errors
     *
     * @param object $errors
     * @return object
     */
    public function returnErrorsFromValidation(object $errors)
    {
        if (count($errors)) {

            $getErrors = [];
            foreach ($errors as $error) {
                $getErrors[] = [$error->getPropertyPath() => $error->getMessage()];
            }

            return $this->presentError('There are validation errors', $getErrors);
        } else {
            return $this->present("There is not validation errors");
        }
    }

    protected function json($data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }
}
