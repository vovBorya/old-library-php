<?php

namespace App\Controllers;


class Controller {

    private $requestMethod;
    private $resourceId;

    private $gateway;

    public function __construct($requestMethod, $resourceId, $gateway = null)
    {
        $this->requestMethod = $requestMethod;
        $this->resourceId = $resourceId;
        $this->gateway = $gateway;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if ($this->resourceId) {
                    $response = $this->getOne($this->resourceId);
                } else {
                    $response = $this->getAll();
                };
                break;
            case 'POST':
                $response = $this->create();
                break;
            case 'PUT':
                $response = $this->update($this->resourceId);
                break;
            case 'DELETE':
                $response = $this->delete($this->resourceId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getAll(): array {
        $result = $this->gateway->findAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }

    private function getOne($id): array {
        $result = $this->gateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result[0]);
        return $response;
    }

    private function create(): array {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        $result = $this->gateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($result[0]);
        return $response;
    }

    private function update($id): array
    {
        $result = $this->gateway->find($id);

        if (! $result) {
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
//        if (! $this->validatePerson($input)) {
//            return $this->unprocessableEntityResponse();
//        }
        $this->gateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';

        $updated = $this->gateway->find($id);

        $response['body'] = json_encode($updated[0]);
        return $response;
    }

    private function delete($id): array
    {
        $result = $this->gateway->find($id);
        if (! $result) {
            return $this->notFoundResponse();
        }
        $this->gateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($id);
        return $response;
    }

    private function validatePerson($input): bool
    {
//        if (! isset($input['firstname'])) {
//            return false;
//        }
//        if (! isset($input['lastname'])) {
//            return false;
//        }
        return true;
    }

    private function unprocessableEntityResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse(): array
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }

    /**
     * @param mixed|null $gateway
     */
    public function setGateway($gateway)
    {
        $this->gateway = $gateway;
    }
}