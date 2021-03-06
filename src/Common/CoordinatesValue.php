<?php

namespace G4\DataMapper\Common;

class CoordinatesValue implements ValueInterface
{
    private $longitude;
    private $latitude;
    private $distance;

    public function __construct($longitude, $latitude, $distance)
    {
        $this->longitude = $longitude;
        $this->latitude  = $latitude;
        $this->distance  = $distance;
    }

    public function formatForSolr()
    {
        return [
            'fq'     => '{!geofilt}',
            'sfield' => 'location',
            'pt'     => $this->longitude . QueryConnector::COMMA . $this->latitude,
            'd'      => $this->distance,
        ];
    }

    public function getLongitude()
    {
        return $this->longitude;
    }

    public function getLatitude()
    {
        return $this->latitude;
    }

    public function getDistance()
    {
        return $this->distance;
    }

    public function isEmpty()
    {
        return $this->longitude === null || $this->latitude === null || $this->distance === null;
    }
}
