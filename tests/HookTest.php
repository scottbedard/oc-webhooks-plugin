<?php namespace Bedard\Shop\Tests\Models;

use Exception;
use Carbon\Carbon;
use PluginTestCase;
use Bedard\Webhooks\Models\Log;
use Bedard\Webhooks\Models\Hook;

class HookTest extends PluginTestCase
{
    public function test_hooks_are_created_with_a_random_token()
    {
        $hook = Hook::create(['http_method' => 'post']);
        $this->assertTrue((bool) $hook->token);
    }

    public function test_executing_a_script_and_logging_the_output()
    {
        $hook = Hook::create(['script' => 'echo 12345']);
        $this->assertNull($hook->executed_at);
        $hook->execute();

        $log = Log::whereHookId($hook->id)->first();
        $this->assertNotNull($hook->executed_at);
        $this->assertEquals(12345, $log->output);
    }

    public function test_joining_the_log_count_to_hooks()
    {
        $dummy1 = Hook::create(['script' => 'echo 67890']);
        $dummy2 = Hook::create(['script' => 'echo 12345']);
        $dummy2->execute();

        $this->assertEquals(0, Hook::joinLogsCount()->find($dummy1->id)->logsCount);
        $this->assertEquals(1, Hook::joinLogsCount()->find($dummy2->id)->logsCount);
    }

    public function test_executing_a_script_with_or_without_a_directory()
    {
        $dummy1 = Hook::create(['script' => 'echo 1', 'directory' => '']);
        $dummy2 = Hook::create(['script' => 'echo 1', 'directory' => '/']);
        $this->assertTrue($dummy1->execute());
        $this->assertTrue($dummy2->execute());
    }

    public function test_http_accessors()
    {
        $hook = Hook::create(['http_method' => 'GET']);
        $this->assertEquals('GET', $hook->httpMethod);
        $this->assertTrue(preg_match('/(.*)\/bedard\/webhooks\/(\w{40})/', $hook->url) === 1);
    }
}
