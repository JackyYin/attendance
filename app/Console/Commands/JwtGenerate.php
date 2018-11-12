<?php

namespace App\Console\Commands;

use Tymon\JWTAuth\JWTAuth;
use Illuminate\Console\Command;
use App\Models\Agent;

class JwtGenerate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'jwt:generate
        {agent : name of agent }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Jwt Token For Agent';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(JWTAuth $jwt)
    {
        parent::__construct();

        $this->jwt = $jwt;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $agent = Agent::where('name', $this->argument('agent'))->first();

        if (!$agent) {
            $this->error('不存在的Agent');
            return;
        }

        try {

            $this->jwt->factory()->setTTL(60 * 24 * 365);

            if (! $token = $this->jwt->fromUser($agent)) {
                $this->error('invalid_credentials');
            }
        } catch (\Exception $e) {
            $this->error('could_not_create_token');
        }
        $this->info($token);
    }
}

