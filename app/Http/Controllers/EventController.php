<?php

namespace App\Http\Controllers;

use App\Services\EventService;
use App\Services\LeadService;
use App\Http\Requests\CreateEventRequest;
use App\Http\Requests\FilterEventRequest;
use App\Http\Requests\UpdateEventRequest;
use Illuminate\Http\JsonResponse;

class EventController extends Controller
{
    public function __construct(
        private EventService $eventService,
        private LeadService $leadService
    )
    {}

    public function create(CreateEventRequest $request): JsonResponse
    {
        return response()->json($this->eventService->create($request->all()));
    }

    public function getAll(FilterEventRequest $request): JsonResponse
    {   
        return response()->json($this->leadService->getLeadsWithEvents($request->validated()));
    }

    public function find(int $id): JsonResponse
    {
        return response()->json($this->eventService->find($id));
    }

    public function update(int $id, UpdateEventRequest $request): JsonResponse
    {
        return response()->json($this->eventService->update($id, $request->all()));
    }

    public function delete(int $id)
    {
        return response()->json($this->eventService->delete($id));
    }

    public function getEventsByUserId(FilterEventRequest $request): JsonResponse
    {
        return response()->json($this->leadService->getLeadsWithEventsByUserId($request->validated()));
    }
}