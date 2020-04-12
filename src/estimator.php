<?php

function covid19ImpactEstimator($data)
{
  $data = json_decode($data, true);
  $response = array();

  $response['impact']['currentlyInfected'] = (int)$data['reportedCases'] * 10;

  $response['data'] = $data;
  $response['severeImpact']['currentlyInfected'] = (int)$data['reportedCases'] * 50;
  return $response;
}