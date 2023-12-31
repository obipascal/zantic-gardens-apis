<?php

namespace App\Http\Controllers\Payments;

use App\Http\Config\RESTResponse;
use App\Http\Controllers\Controller;
use App\Http\Handlers\Handlers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TransactionApi extends Controller
{
	use RESTResponse;
	/**
	 * Display a listing of the resource.
	 */
	public function index()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 */
	public function store(Request $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		try {
			/* Call the controller handlers to handle request logic */
			$handler = Handlers::Payments(request())->fetchTransaction($id);

			/* Determine handler operation status  */
			if (!$handler->STATE) {
				/* If operation didn't succeed return the error that was generated by the operation */
				return $this->terminateRequest($handler->ERROR, $handler->RESPONSE, $handler->CODE);
			}

			/* Finally all went well return the response to caller/client */
			return $this->sendResponse($handler->RESPONSE, $handler->MESSAGE, $handler->STATE, $handler->CODE);
		} catch (\Exception $th) {
			Log::error($th->getMessage(), ["file" => $th->getFile(), "line" => $th->getLine()]);

			return $this->terminateRequest("ERROR", $this->RESPONSE ?? $th->getMessage(), 500);
		}
	}

	/**
	 * Update the specified resource in storage.
	 */
	public function update(Request $request, string $id)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 */
	public function destroy(string $id)
	{
		//
	}
}
