<?php

namespace App\Http\Controllers\Misc;

use App\Http\Config\RESTResponse;
use App\Http\Controllers\Controller;
use App\Http\Handlers\Handlers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ReviewsApis extends Controller
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
		try {
			/* Run validation  */
			$validator = Validator::make($request->all(), [
				"room_id" => ["bail", "numeric", "required", "exists:hotel_rooms,room_id"],
				"rating" => ["bail", "numeric", "required"],
				"message" => ["bail", "string", "required"],
			]);

			/* Check if any validation fails */
			if ($validator->fails()) {
				/* If fails return the validation error message  */
				return $this->terminateRequest("Validation Error", $this->getValidationMessages($validator));
			}
			/* Call the controller handlers to handle request logic */
			$handler = Handlers::Reviews($request)->createReview();

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
	 * Display the specified resource.
	 */
	public function show(string $id)
	{
		try {
			/* Run validation  */
			$validator = Validator::make(
				["room_id" => $id],
				[
					"room_id" => ["bail", "numeric", "required", "exists:hotel_rooms,room_id"],
					"perPage" => ["bail", "numeric", "nullable"],
				]
			);

			/* Check if any validation fails */
			if ($validator->fails()) {
				/* If fails return the validation error message  */
				return $this->terminateRequest("Validation Error", $this->getValidationMessages($validator));
			}
			/* Call the controller handlers to handle request logic */
			$handler = Handlers::Reviews(request())->fetchReviews($id);

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