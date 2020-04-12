<?php

function covid19ImpactEstimator($data)
{
  $data = json_decode($data, true);
  $response = array();

  $response['impact']['currentlyInfected'] = (int)$data['reportedCases'] * 10;
  $response['data'] = $data;
  $response['severeImpact']['currentlyInfected'] = (int)$data['reportedCases'] * 50;

  $timeToElapse = $data['timeToElapse'];

  if ($data['periodType'] === 'days') {
    $_factor = $timeToElapse / 3;
    $_days = $timeToElapse;
  }

  if ($data['periodType'] === 'weeks') {
      $_factor = ($timeToElapse * 7) / 3;
      $_days = $timeToElapse * 7;
  }

  if ($data['periodType'] === 'months') {
      $_factor = ($timeToElapse * 30) / 3;
      $_days = $timeToElapse * 30;
  }

  $response['impact']['infectionsByRequestedTime'] = (int)($data['reportedCases'] * 10 * pow(2,$_factor));
  $response['severeImpact']['infectionsByRequestedTime'] = (int)($data['reportedCases'] * 50 * pow(2,$_factor));

  $response['impact']['severeCasesByRequestedTime'] = (int)($response['impact']['infectionsByRequestedTime'] * 15/100);
  $response['severeImpact']['severeCasesByRequestedTime'] = (int)($response['severeImpact']['infectionsByRequestedTime'] * 15/100);

  

  return $response;
}