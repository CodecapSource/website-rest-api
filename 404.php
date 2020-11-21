<?php

header("Content-Type: application/json; charset=UTF-8");
die(json_encode(["status" => 404, "data" => "Sorry, page is not found"]));
