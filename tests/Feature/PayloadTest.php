<?php

namespace Elnooronline\Peak\Tests\Feature;

use Elnooronline\Peak\Payloads\JsonPayload;
use Elnooronline\Peak\Payloads\RawPayload;
use Elnooronline\Peak\Tests\TestCase;

class PayloadTest extends TestCase
{
    public function test_json_payload(): void
    {
        $json = new JsonPayload();

        $this->assertTrue($json->isEmpty());

        $json->set('hello', 'world');

        $this->assertTrue($json->isNotEmpty());
        $this->assertEquals($json->all(), ['hello' => 'world']);

        $json->set('foo', 'bar');

        $json->remove('foo');

        $this->assertArrayNotHasKey('foo', $json->all());
    }

    public function test_raw_payload(): void
    {
        $raw = new RawPayload();

        $this->assertTrue($raw->isEmpty());

        $raw->set('hello world');

        $this->assertSame('hello world', $raw->all());

        $raw->push('!');

        $this->assertSame('hello world!', (string) $raw);

        $this->expectException(\LogicException::class);
        $raw->set('foo', 'bar');

        $this->expectException(\LogicException::class);
        $raw->merge('abc');

        $this->expectException(\LogicException::class);
        $raw->remove('foo');
    }
}