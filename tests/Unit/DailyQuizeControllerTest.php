<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Controllers\Api\DailyQuizeController;

class DailyQuizeControllerTest extends TestCase
{
    
        public function testDailyQuize()
        {
            $quizController = new DailyQuizeController();
            $actualResponse = json_decode($quizController->dailyQuize()->getContent(), true);
            $expectedResponse = [
                "data" => [
                    "question_id" => 9,
                    "subject" => "Bengali",
                    "question" => "'গোবর গণেশ' বাগধারাটির অর্থ কি?",
                    "options" => [
                        [
                            "option_id" => 1272,
                            "options" => "অপটু",
                        ],
                        [
                            "option_id" => 1273,
                            "options" => "অপদার্থ",
                        ],
                        [
                            "option_id" => 1274,
                            "options" => "নিরেট মূর্খ",
                        ],
                        [
                            "option_id" => 1275,
                            "options" => "অত্যন্ত অলস",
                        ],
                    ],
                    "date" => "4th January 2024",
                    "question_type" => "multiple option quistion",
                ],
            ];


            $this->assertEquals($expectedResponse, $actualResponse);
        }
   
}
