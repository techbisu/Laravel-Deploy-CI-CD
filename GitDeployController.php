<?php

namespace App\Http\Controllers\Api\Config;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Process\Process;
use Illuminate\Http\Response;
use Symfony\Component\Process\Exception\ProcessFailedException;

class GitDeployController extends Controller
{
    public function deployx(Request $request)
    {
        try {
            $githubPayload = $request->getContent();
            $githubHash = $request->header('X-Hub-Signature');

            $localToken = config('app.deploy_secret');
            $localHash = 'sha1=' . hash_hmac('sha1', $githubPayload, $localToken, false);

            if (!hash_equals($githubHash, $localHash)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }

            // Change directory to root path
            $root_path = base_path();
            chdir($root_path);

            // Execute the shell script
            $process = new Process(['./deploy.sh']);
            $process->run();

            // Check if the process was successful
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            // Process output
            $output = $process->getOutput();

            // Return success response
            return response()->json(['message' => 'Deployment successful', 'output' => $output], 200);
        } catch (ProcessFailedException $e) {
            // Log the error or handle it as needed
            return response()->json(['error' => 'Deployment failed', 'output' => $e->getMessage()], 500);
        } catch (\Exception $e) {
            // Log the exception or handle it as needed
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }
}
