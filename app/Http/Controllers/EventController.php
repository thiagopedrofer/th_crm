<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\FilterEventRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(private EventService $eventService)
    {}

    public function create(CreateEventRequest $request): JsonResponse
    {
        return response()->json($this->eventService->create($request->all()));
    }

    public function getAll(FilterEventRequest $request): JsonResponse
    {   
        return response()->json($this->eventService->getAll($request->validated()));
    }

    public function find(int $id): JsonResponse
    {
        return response()->json($this->eventService->find($id));
    }

    public function update(int $id, Request $request): JsonResponse
    {
        return response()->json($this->eventService->update($id, $request->all()));
    }

    public function delete(int $id)
    {
        return response()->json($this->eventService->delete($id));
    }

    public function getEventsByUserId(FilterEventRequest $request): JsonResponse
    {
        return response()->json($this->eventService->getEventsByUserId($request->validated()));
    }
}