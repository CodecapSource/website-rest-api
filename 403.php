<?php

header("Content-Type: application/json; charset=UTF-8");
die(json_encode(["status" => 403, "data" => "Sorry, you don't have permission to access this route"]));
