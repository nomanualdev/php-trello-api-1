<?php

declare(strict_types=1);

namespace Semaio\TrelloApi\Tests\Client;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Psr\Http\Message\StreamInterface;
use Semaio\TrelloApi\Client\HttpClient;
use Semaio\TrelloApi\Client\HttpClientInterface;

/**
 * Class HttpClientTest.
 */
class HttpClientTest extends TestCase
{
    /**
     * @var ClientInterface|MockObject
     */
    protected $baseHttpClient;

    /**
     * @var RequestFactoryInterface|MockObject
     */
    protected $requestFactory;

    /**
     * @var StreamFactoryInterface|MockObject
     */
    protected $streamFactory;

    /**
     * @var HttpClient
     */
    protected $httpClient;

    protected function setUp(): void
    {
        $this->baseHttpClient = $this->createMock(ClientInterface::class);
        $this->requestFactory = $this->createMock(RequestFactoryInterface::class);
        $this->streamFactory = $this->createMock(StreamFactoryInterface::class);

        $this->httpClient = new HttpClient(
            $this->baseHttpClient,
            $this->requestFactory,
            $this->streamFactory
        );
    }

    /**
     * @test
     */
    public function it_can_create_http_client(): void
    {
        $client = HttpClient::create($this->baseHttpClient, $this->requestFactory, $this->streamFactory);

        static::assertInstanceOf(HttpClientInterface::class, $client);
    }

    /**
     * @test
     */
    public function it_can_send_request_with_string_body(): void
    {
        $httpMethod = 'POST';
        $uri = 'api/3/contacts';
        $body = 'the body';

        /** @var MockObject|RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);

        $this->requestFactory->expects(static::once())
            ->method('createRequest')
            ->with($httpMethod, $uri)
            ->willReturn($request);

        /** @var MockObject|StreamInterface $stream */
        $stream = $this->createMock(StreamInterface::class);

        $this->streamFactory->expects(static::once())
            ->method('createStream')
            ->with($body)
            ->willReturn($stream);

        $request->expects(static::once())
            ->method('withBody')
            ->with($stream)
            ->willReturnSelf();

        /** @var MockObject|ResponseInterface $response */
        $response = $this->createMock(ResponseInterface::class);

        $this->baseHttpClient->expects(static::once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $this->httpClient->sendRequest($httpMethod, $uri, [], $body);
    }

    /**
     * @test
     */
    public function it_can_send_request_with_stream_body(): void
    {
        $httpMethod = 'POST';
        $uri = 'api/3/contacts';
        /** @var MockObject|StreamInterface $body */
        $body = $this->createMock(StreamInterface::class);

        /** @var MockObject|RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);

        $this->requestFactory->expects(static::once())
            ->method('createRequest')
            ->with($httpMethod, $uri)
            ->willReturn($request);

        $request->expects(static::once())
            ->method('withBody')
            ->with($body)
            ->willReturnSelf();

        /** @var MockObject|ResponseInterface $response */
        $response = $this->createMock(ResponseInterface::class);

        $this->baseHttpClient->expects(static::once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $this->httpClient->sendRequest($httpMethod, $uri, [], $body);
    }

    /**
     * @test
     */
    public function it_can_send_request(): void
    {
        $httpMethod = 'POST';
        $uri = 'api/3/contacts';
        $headers = ['header' => 'content'];

        /** @var MockObject|RequestInterface $request */
        $request = $this->createMock(RequestInterface::class);

        $this->requestFactory->expects(static::once())
            ->method('createRequest')
            ->with($httpMethod, $uri)
            ->willReturn($request);

        $request->expects(static::once())
            ->method('withHeader')
            ->with('header', 'content')
            ->willReturnSelf();

        /** @var MockObject|ResponseInterface $response */
        $response = $this->createMock(ResponseInterface::class);

        $this->baseHttpClient->expects(static::once())
            ->method('sendRequest')
            ->with($request)
            ->willReturn($response);

        $this->httpClient->sendRequest($httpMethod, $uri, $headers, null);
    }
}
