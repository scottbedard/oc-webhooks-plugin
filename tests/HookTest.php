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
        $hook->execute();

        $log = Log::whereHookId($hook->id)->first();
        $this->assertEquals($hook->executed_at, Carbon::now());
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
}
