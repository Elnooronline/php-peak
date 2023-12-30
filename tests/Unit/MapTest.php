<?php

namespace Elnooronline\Peak\Tests\Unit;

use Elnooronline\Peak\Support\Map;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{
    private Map $map;

    private array $data;

    public function setUp(): void
    {
        parent::setUp();

        $this->data = [
            'name' => 'elnooronline',
            'hello' => 'world',
            'team' => [
                'age' => 21,
                'members' => [
                    'name' => 'abdallah mohammed',
                ],
            ],
        ];

        $this->map = new Map($this->data);
    }

    public function test_get_all_data()
    {
        $this->assertEquals(
            $this->map->all(),
            $this->data
        );

        $this->assertEquals(
            $this->map->get(),
            $this->data
        );
    }

    public function test_get_an_element()
    {
        $this->assertEquals(
            $this->map->get('hello'),
            'world',
        );

        $this->assertEquals(
            $this->map->get('team.age'),
            21
        );

        $this->assertEquals(
            $this->map->get('team.members.name'),
            'abdallah mohammed'
        );

        $this->assertEquals(
            $this->map->get('team.name', 'elnooronline'),
            'elnooronline'
        );
    }

    public function test_has_method()
    {
        $this->assertFalse(
            $this->map->has('age')
        );

        $this->assertTrue(
            $this->map->has('name')
        );
    }

    public function test_append_element_to_map()
    {
        $this->map->set('since', '2011');

        $this->assertArrayHasKey(
            'since',
            $this->map->all()
        );

        $this->map->set($key = 'this_element_is_located_at_the_end');

        $this->assertArrayNotHasKey(
            $key,
            $this->map->all()
        );

        $this->assertEquals(
            $this->map->last(),
            $key,
        );
    }

    public function test_remove_element()
    {
        $this->assertArrayNotHasKey(
            'name',
            $this->map->remove('name')->all()
        );
    }

    public function test_merge_array_and_elements()
    {
        $this->map->merge(['hello2' => 'world'], ['team2' => []])
            ->merge([1], [2], [3]);

        $this->assertArrayHasKey(
            'hello2',
            $this->map->all()
        );

        $this->assertIsArray($this->map->get('team2'));

        $this->assertEquals(
            3,
            $this->map->last()
        );
    }

    public function test_count_array_elements()
    {
        $this->assertEquals(
            3,
            $this->map->count()
        );
    }

    public function test_empty_and_not_empty_methods()
    {
        $this->assertFalse($this->map->isEmpty());
        $this->assertTrue($this->map->isNotEmpty());
    }

    public function test_magic_methods()
    {
        $this->assertEquals(
            $this->map->name,
            'elnooronline'
        );

        $this->assertNotEmpty($this->map->team);

        $this->map->magic_key = 'iam_magic_key';

        $this->assertEquals(
            $this->map->get('magic_key'),
            'iam_magic_key'
        );

        $this->assertEquals(
            $this->map['name'],
            'elnooronline'
        );

        $this->assertTrue(isset($this->map['name']));

        $this->map['magic_key_2'] = 'iam_magic_key_2';

        $this->assertEquals(
            $this->map->get('magic_key_2'),
            'iam_magic_key_2'
        );
    }
}
