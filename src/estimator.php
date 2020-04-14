<?php

function covid19ImpactEstimator($data)
{
  // $data = json_decode($data, true);
  $response = array();

  $response['impact']['currentlyInfected'] = (int)$data['reportedCases'] * 10;
  $response['data'] = $data;
  $response['severeImpact']['currentlyInfected'] = (int)$data['reportedCases'] * 50;

  $timeToElapse = $data['timeToElapse'];

  if ($data['periodType'] === 'days') {
    $elapsedDays = $timeToElapse;
  }

  if ($data['periodType'] === 'weeks') {
      $elapsedDays = $timeToElapse * 7;
  }

  if ($data['periodType'] === 'months') {
    $elapsedDays = $timeToElapse * 30;
  }

  $_factor = intdiv($elapsedDays,3);

  $response['impact']['infectionsByRequestedTime'] = (int)($data['reportedCases'] * 10 * pow(2,$_factor));
  $response['severeImpact']['infectionsByRequestedTime'] = (int)($data['reportedCases'] * 50 * pow(2,$_factor));

  $response['impact']['severeCasesByRequestedTime'] = (int)($response['impact']['infectionsByRequestedTime'] * 0.15);
  $response['severeImpact']['severeCasesByRequestedTime'] = (int)($response['severeImpact']['infectionsByRequestedTime'] * 0.15);

  $response['impact']['hospitalBedsByRequestedTime'] = (int)(($data['totalHospitalBeds'] * 35/100) - $response['impact']['severeCasesByRequestedTime']);
  $response['severeImpact']['hospitalBedsByRequestedTime'] = (int)(($data['totalHospitalBeds'] * 35/100) - $response['severeImpact']['severeCasesByRequestedTime']);

  $response['impact']['casesForICUByRequestedTime'] = (int)($response['impact']['infectionsByRequestedTime'] * 5/100);
  $response['severeImpact']['casesForICUByRequestedTime'] = (int)($response['severeImpact']['infectionsByRequestedTime'] * 5/100);

  $response['impact']['casesForVentilatorsByRequestedTime'] = (int)($response['impact']['infectionsByRequestedTime'] * 2/100);
  $response['severeImpact']['casesForVentilatorsByRequestedTime'] = (int)($response['severeImpact']['infectionsByRequestedTime'] * 2/100);

  $_avgDailyIncomeInUSD = $data['region']['avgDailyIncomeInUSD'];
  $_avgDailyIncomePopulation = $data['region']['avgDailyIncomePopulation'];

  $response['impact']['dollarsInFlight'] = (int)( ($response['impact']['infectionsByRequestedTime'] * $_avgDailyIncomePopulation  * $_avgDailyIncomeInUSD ) / $elapsedDays );
  $response['severeImpact']['dollarsInFlight'] = (int)(($response['severeImpact']['infectionsByRequestedTime'] * $_avgDailyIncomePopulation  * $_avgDailyIncomeInUSD ) / $elapsedDays );

  return $response;
}