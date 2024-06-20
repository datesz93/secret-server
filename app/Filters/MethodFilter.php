<?php
namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class MethodFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $method = $request->getMethod();
        if (!in_array(strtolower($method), ['post', 'get'])) {
            return \Config\Services::response()
                ->setStatusCode(405)
                ->setBody('Invalid input');
        }

        if ($method === 'post') {
            $contentType = $request->getHeaderLine('Content-Type');
            if (strpos(strtolower($contentType), 'application/x-www-form-urlencoded') !== 0) {
                return \Config\Services::response()
                    ->setStatusCode(415)
                    ->setBody('Unsupported Media Type');
            }
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
