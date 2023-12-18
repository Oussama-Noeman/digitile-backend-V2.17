<?php

namespace App\Http\Controllers\Api\V1\Platform;

use App\Http\Controllers\Controller;
use App\Models\Tenant\ProductProduct;
use App\Models\Tenant\ResourceCalendar;
use App\Models\Tenant\ResourceCalendarAttendance;
use App\Models\User;
use App\Utils\CustomHelper;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class CalendarController extends Controller
{
    public function formatTimeFromFloat($floatTime)
    {
        $hours = intval($floatTime);
        $minutes = round(($floatTime - $hours) * 60);

        // Using Carbon for easy time formatting
        $formattedTime = Carbon::createFromTime($hours, $minutes)->format('H:i');

        return $formattedTime;
    }
    public function timeToDecimal($timeStr)
    {
        list($hours, $minutes) = array_map('intval', explode(':', $timeStr));

        $decimalTime = $hours + ($minutes / 60);

        return $decimalTime;
    }
    public function getMaxPreparationTime($listProducts)
    {
        $products = ProductProduct::whereIn('id', $listProducts)->get();
        $maxPreparationTime = 0;

        foreach ($products as $product) {
            if ($product->preparing_time) {
                $preparationTimeInMinutes = intval($product->preparing_time);
                if ($maxPreparationTime < $preparationTimeInMinutes) {
                    $maxPreparationTime = $preparationTimeInMinutes;
                }
            }
        }

        $maxPreparationTime = $maxPreparationTime / 60;

        return $maxPreparationTime;
    }


    public function createTimeSlots($opening_time, $closing_time, $slot_duration, $as_soon_as_possible = null, $soon_possible_time = null)
    {
        $opening_time = Carbon::createFromFormat('H:i', $opening_time);
        $closing_time = Carbon::createFromFormat('H:i', $closing_time);

        // Initialize a list to store time slots
        $time_slots = [];

        $current_time = $opening_time->copy();

        // Define the time duration for each slot
        // $slot_duration = Carbon::interval($slot_duration_minutes . 'm');

        if ($as_soon_as_possible) {
            $values = [
                'from' => $soon_possible_time,
                'to' => $soon_possible_time,
            ];
            $time_slots[] = $values;
            return $time_slots;
        }

        if ($current_time->eq($closing_time)) {
            $values = [
                'from' => $closing_time->format('H:i'),
                'to' => $closing_time->format('H:i'),
            ];
            $time_slots[] = $values;
            return $time_slots;
        }

        while ($current_time->lt($closing_time)) {
            $to_time = $current_time->copy()->addMinutes($slot_duration);

            if ($to_time->gt($closing_time)) {
                $to_time = $closing_time;
            }

            $values = [
                'from' => $current_time->format('H:i'),
                'to' => $to_time->format('H:i'),
            ];

            $time_slots[] = $values;
            $current_time->addMinutes($slot_duration);
        }

        return $time_slots;
    }



    public function getTimeSlot(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|integer',
            'dayofweek' => 'required',
            'is_tomorrow' => 'required',
            'is_delivery' => 'required',
            'list_products' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();
            return response()->json([
                'response' => $errors,
                'message' => 'An error Occurred!'
            ], Response::HTTP_NOT_FOUND);
        }
        $req = $request->all();
        $company_id = $req['company_id'];
        $calendar = ResourceCalendar::where('company_id', $company_id)
            ->where('is_working_day', true)
            ->where('active', true)
            ->get();

        $dayOfWeek = $req['dayofweek'];
        $isTomorrow = $req['is_tomorrow'];
        $listProducts = $req['list_products'];
        $isDelivery = $req['is_delivery'];

        $nowUtc = Carbon::now('UTC');
        $currentTime = $nowUtc->copy()->timezone(env('APP_TIMEZONE'));
        $timeStr = $currentTime->format('H:i');

        if ($isDelivery) {

            $deliveryTime = 30 / 60;
            $detail = new CustomHelper();
            // try {
            if (!isset($req['zone_id'])) {
                return response()->json([
                    'response' => [],
                    'message' => 'Zone Not Defined!'
                ], Response::HTTP_NOT_FOUND);
            }
            $zoneId = $req['zone_id'];
            $deliveryTime = $detail->getDeliveryTime($company_id, $zoneId) / 60;
        } else {
            $deliveryTime = 0;
        }

        $decimalTime = $this->timeToDecimal($timeStr);

        if ($dayOfWeek > 6) {
            return response()->json([
                'response' => [],
                'message' => 'No Time Slot!'
            ], Response::HTTP_NOT_FOUND);
        }

        if ($isTomorrow) {
            $dayOfWeek = $dayOfWeek + 1 <= 5 ? $dayOfWeek + 1 : 0;
        }

        $companyScheduleTime = [];
        $companyFullScheduleTime = [];

        if ($calendar->isNotEmpty()) {
            $calendarAttendance = ResourceCalendarAttendance::where('calendar_id', $calendar[0]->id)
                ->where('dayofweek', $dayOfWeek)
                ->orderBy('hour_from', 'ASC')
                ->get();

            $orderOk = false;
            foreach ($calendarAttendance as $cal) {
                $hour_to_parset = Carbon::createFromFormat('H:i:s', $cal->hour_to)->format('G') + Carbon::createFromFormat('H:i:s', $cal->hour_to)->format('i') / 60;

                if ($decimalTime <= $hour_to_parset) {
                    $orderOk = true;
                    break;
                }
            }

            if (!$orderOk) {
                return response()->json([
                    'response' => [
                        'schedule_time' => [],
                        'estimated_time' => ''
                    ],
                    'message' => 'Order out of time!'
                ], Response::HTTP_NOT_FOUND);
            }

            $maxPreparationTime = $this->getMaxPreparationTime($listProducts);
            $fromTime = $decimalTime + $maxPreparationTime + $deliveryTime;
            $estimatedTime = false;

            if ($calendarAttendance->isNotEmpty()) {
                $index = 0;

                foreach ($calendarAttendance as $att) {
                    $index++;

                    $hour_from_parset = Carbon::createFromFormat('H:i:s', $att->hour_from)->format('G') + Carbon::createFromFormat('H:i:s', $att->hour_from)->format('i') / 60;
                    $hour_to_parset = Carbon::createFromFormat('H:i:s', $att->hour_to)->format('G') + Carbon::createFromFormat('H:i:s', $att->hour_to)->format('i') / 60;

                    if (!$isTomorrow) {
                        if ($fromTime <= $hour_from_parset) {
                            $fromTime = $hour_from_parset + $maxPreparationTime + $deliveryTime;
                            list($integerPart, $decimalPart) = $this->splitFloat($fromTime);

                            if ($estimatedTime === false) {
                                $estimatedTime = $this->formatTimeFromFloat($fromTime);
                            }

                            if ($decimalPart > 0.5) {
                                $fromTime = $integerPart + 1;
                            } elseif ($decimalPart > 0 && $decimalPart < 0.5) {
                                $fromTime = $integerPart + 0.5;
                            }

                            $companyScheduleTime[] = $this->createTimeSlots(
                                $this->formatTimeFromFloat($fromTime),
                                $this->formatTimeFromFloat($hour_to_parset),
                                30
                            );

                            continue;
                        } elseif ($fromTime > $hour_from_parset && $fromTime <= $hour_to_parset) {
                            if ($decimalTime <= $hour_from_parset) {
                                $fromTime = $hour_from_parset + $maxPreparationTime + $deliveryTime;
                            }

                            list($integerPart, $decimalPart) = $this->splitFloat($fromTime);
                            $initialFromTime = $fromTime;

                            if ($estimatedTime === false) {
                                $estimatedTime = $this->formatTimeFromFloat($fromTime);
                            }

                            if ($decimalPart > 0.5) {
                                $fromTime = $integerPart + 1;
                            } elseif ($decimalPart > 0 && $decimalPart < 0.5) {
                                $fromTime = $integerPart + 0.5;
                            }

                            if ($fromTime > $hour_to_parset) {
                                $fromTime = $initialFromTime;
                            }

                            if ($fromTime != $initialFromTime) {
                                $companyScheduleTime[] = $this->createTimeSlots(
                                    $this->formatTimeFromFloat($fromTime),
                                    $this->formatTimeFromFloat($hour_to_parset),
                                    30
                                );
                            }

                            continue;
                        } else {
                            if ($fromTime > $hour_to_parset) {
                                if ($decimalTime <= $hour_to_parset) {
                                    $companyScheduleTime[] = $this->createTimeSlots(
                                        $this->formatTimeFromFloat($fromTime),
                                        $this->formatTimeFromFloat($hour_to_parset),
                                        30,
                                        true,
                                        $this->formatTimeFromFloat($fromTime)
                                    );

                                    if ($estimatedTime === false) {
                                        $estimatedTime = $this->formatTimeFromFloat($fromTime);
                                    }

                                    continue;
                                } else {
                                    continue;
                                }
                            }
                        }
                    } else {
                        $fromTime = $hour_from_parset + $deliveryTime + $maxPreparationTime;
                        list($integerPart, $decimalPart) = $this->splitFloat($fromTime);

                        if ($estimatedTime === false) {
                            $estimatedTime = $this->formatTimeFromFloat($fromTime);
                        }

                        if ($decimalPart > 0.5) {
                            $fromTime = $integerPart + 1;
                        } elseif ($decimalPart > 0 && $decimalPart < 0.5) {
                            $fromTime = $integerPart + 0.5;
                        }

                        $companyScheduleTime[] = $this->createTimeSlots(
                            $this->formatTimeFromFloat($fromTime),
                            $this->formatTimeFromFloat($hour_to_parset),
                            30
                        );

                        continue;
                    }
                }

                foreach ($companyScheduleTime as $sch) {
                    foreach ($sch as $item) {
                        $companyFullScheduleTime[] = $item;
                    }
                }

                $values = [
                    'schedule_time' => $companyFullScheduleTime,
                    'estimated_time' => $estimatedTime,
                ];

                return response()->json([
                    'response' => $values,
                    'message' => 'Success'
                ], Response::HTTP_OK);
            } else {
                return response()->json([
                    'response' => [],
                    'message' => 'No Time Slot!'
                ], Response::HTTP_NOT_FOUND);
            }
        }
    }
    private function splitFloat($floatNumber)
    {
        $integerPart = floor($floatNumber);
        $decimalPart = $floatNumber - $integerPart;

        return [$integerPart, $decimalPart];
    }
}
