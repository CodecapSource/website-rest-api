<?php

header("Content-Type: application/json; charset=UTF-8");
die(json_encode(["status" => 500, "data" => "Sorry, something went wrong with the server"]));
