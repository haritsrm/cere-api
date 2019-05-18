<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Kreait\Firebase;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use Kreait\Firebase\Database;

class TeacherController extends Controller
{
    public function index(){
    	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/cerebrum-220815-firebase-adminsdk-603hw-038341c27e.json');
    	$firebase 		  = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://cerebrum-220815.firebaseio.com')
                        ->create();
        $database = $firebase->getDatabase();
		$reference = $database->getReference('/teachers');
		$snapshot = $reference->getSnapshot()->getValue();
		// echo"<pre>";
		// print_r($newPost->getvalue());
		return response($snapshot);
    }

    public function create(){
    	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/cerebrum-220815-firebase-adminsdk-603hw-038341c27e.json');
    	$firebase 		  = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://cerebrum-220815.firebaseio.com')
                        ->create();
        $database 		= $firebase->getDatabase();
		$newPost 		  = $database
		                    ->getReference('teachers')
		                    ->push([
		                    	'id' => '2',
		                    	'name' => 'Nugraha',
		                    	'address' => 'Manisi',
		                    	'gender' => 'male',
		                    	'photo_url' => 'null',
		                    	'balance' => '0',
		                    	'phone' => '0987654321',
		                    	'email' => 'nugraha@gmail.com',
		                    	'password' => '123456',
		                    	'status' => 'available',
		                    ]);
		return response()->json([
            'message' => 'Successfully created teachers!'
        ], 201);
    }

    public function destroy(){
    	$serviceAccount = ServiceAccount::fromJsonFile(__DIR__.'/cerebrum-220815-firebase-adminsdk-603hw-038341c27e.json');
    	$firebase 		  = (new Factory)
                        ->withServiceAccount($serviceAccount)
                        ->withDatabaseUri('https://cerebrum-220815.firebaseio.com')
                        ->create();
        $database = $firebase->getDatabase();
  		//$reference = $database->getReference('/teachers');
		// $data = $reference->equalTo('-Lf3yczWCupNSKleYC7I');
		// $result = $data->remove();
		$database->getReference('teachers')->remove();
    	return response()->json([
            'message' => 'Successfully deleted teachers!'
        ], 201);
    }	
}
 