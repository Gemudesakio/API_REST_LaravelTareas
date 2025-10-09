<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon; // 👈 importa Carbon

class TaskResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'           => $this->id,
            'title'        => $this->title,
            'description'  => $this->description,
            'is_completed' => (bool) $this->is_completed,

            // 👇 Fechas seguras: si viene Carbon se usa tal cual; si viene string se parsea.
            'completed_at' => $this->toIsoOrNull($this->completed_at),
            'created_at'   => $this->toIsoOrNull($this->created_at),
            'updated_at'   => $this->toIsoOrNull($this->updated_at),

            'tags'         => TagResource::collection($this->whenLoaded('tags')),
        ];
    }

    /**
     * Convierte a ISO-8601 de forma segura (Carbon|string|null -> string|null)
     */
    private function toIsoOrNull($value): ?string
    {
        if (!$value) {
            return null;
        }

        // Si ya es instancia de DateTime/Carbon, conviértelo directo
        if ($value instanceof \DateTimeInterface) {
            return $value->format(DATE_ATOM); // ISO-8601
        }

        // Si es string u otro tipo, intenta parsear a Carbon
        try {
            return Carbon::parse($value)->toIso8601String();
        } catch (\Throwable $e) {
            // Si no se puede parsear, devuélvelo tal cual o null (elige tu política)
            return null;
        }
    }
}
