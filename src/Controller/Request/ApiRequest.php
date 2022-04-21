<?php

namespace App\Controller\Request;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

class ApiRequest
{
    private const SUPPORTED_ENCODERS = [
        'application/json' => 'json',
        'application/xml' => 'xml',
    ];

    private string $regexPattern = '/^(?:api_|_api_|api-|api:|)/';

    private Request $request;

    private FormFactoryInterface $formFactory;

    private SerializerInterface $serializer;

    private FormInterface $form;

    private Response $response;

    public function __construct(RequestStack $requestStack, FormFactoryInterface $formFactory, SerializerInterface $serializer)
    {
        $this->request = $requestStack->getCurrentREquest();
        $this->formFactory = $formFactory;
        $this->serializer = $serializer;
    }

    public function getRegexPattern(): string
    {
        return $this->regexPattern;
    }

    public function setRegexPattern(string $regexPattern): self
    {
        $this->regexPattern = $regexPattern;

        return $this;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function setResponse($data, array $context, int $statusCode, array $headers = []): self
    {
        if (true !== $this->isApi()) {
            $errorExceptionMessage = 'This request can be handled only on the api routes!';
            $this->response = new Response($errorExceptionMessage, Response::HTTP_BAD_REQUEST);
        }

        if (!$this->isJson() || !$this->isXML()) {
            $errorExceptionMessage = implode(',', $this->getEncoders()).
                ' are not supported encoder formats! Supported formats: '.
                implode(',', self::SUPPORTED_ENCODERS);
            $this->response = new Response($errorExceptionMessage, Response::HTTP_UNSUPPORTED_MEDIA_TYPE);
        }

        if ($this->isJson()) {
            $serializedData = $this->serializer->serialize($data, 'json', $context);
            $defaultHeaders = [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ];

            $finalHeaders = array_merge($headers, $defaultHeaders);

            if ($data instanceof FormErrorIterator) {
                $errors = [];

                foreach ($this->form as $child) {
                    foreach ($child->getErrors() as $error) {
                        $errors[$child->getName()][] = $error->getMessage();
                    }
                }

                $this->response = new JsonResponse($errors, $statusCode, $finalHeaders);
            }

            if (!$data instanceof FormErrorIterator) {
                $this->response = new JsonResponse($serializedData, $statusCode, $finalHeaders, true);
            }
        }

        if ($this->isXml()) {
            $serializedData = $this->serializer->serialize($data, 'xml', $context);
            $defaultHeaders = [
                'Content-Type' => 'application/xml',
                'Accept' => 'application/xml',
            ];
            $finalHeaders = array_merge($headers, $defaultHeaders);

            $this->response = new Response($serializedData, $statusCode, $finalHeaders);
        }

        return $this;
    }

    public function getResponse(): Response
    {
        return $this->response;
    }

    public function getEncoders(): array
    {
        return HeaderUtils::split($this->request->headers->get('accept'), ',');
    }

    public function isApi(): bool
    {
        return preg_match($this->regexPattern, $this->request->attributes->get('_route'));
    }

    private function isJson(): bool
    {
        if (!in_array('application/json', $this->getEncoders())) {
            return false;
        }

        return true;
    }

    private function isXml(): bool
    {
        if (!in_array('application/xml', $this->getEncoders())) {
            return false;
        }

        return true;
    }

    public function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        return $this->form = $this->formFactory->create($type, $data, $options);
    }

    public function submitForm(): FormInterface
    {
        return $this->form->submit($this->form->getData());
    }
}
