<?php

namespace App\Enum;
use App\Exception\InvalidArgumentException;

class PowerEnum
{
    public const RR = 'RR';
    public const TT = 'TT';
    public const TB = 'TB';
    public const MTP = 'MTP';
    public const DR = 'DR';
    public const SR = 'SR';
    public const IM = 'IM';
    public const HI = 'HI';
    public const IF = 'IF';
    public const TEL = 'TEL';
    public const IT = 'IT';
    public const CC = 'CC';
    public const DRF = 'DRF';
    public const HSW = 'HSW';
    public const AL = 'AL';
    public const J2F = 'J2F';
    public const NSJ = 'NSJ';
    public const CAL = 'CAL';
    public const STC = 'STC';
    public const IG = 'IG';
    public const BAD = 'BAD';
    public const M = 'M';
    public const INV = 'INV';

    public const POWER_NAME = [
        self::RR => 'Radiation resistance',
        self::TT => 'Turning tiny',
        self::TB => 'Radiation blast',
        self::MTP => 'Million tonne punch',
        self::DR => 'Damage resistance',
        self::SR => 'Superhuman reflexes',
        self::IM => 'Immortality',
        self::HI => 'Heat Immunity',
        self::IF => 'Inferno',
        self::TEL => 'Teleportation',
        self::IT => 'Interdimensional travel',
        self::CC => 'Cheese Control',
        self::DRF => 'Drink really fast',
        self::HSW => 'Hyper slowing writer',
        self::AL => 'Always late',
        self::J2F => 'Jump 2 feets up',
        self::NSJ => 'Never stop jumping',
        self::CAL => 'Cry a lot',
        self::STC => 'Sing to charm',
        self::IG => 'Infernal groove',
        self::BAD => 'Burn all dancfloors',
        self::M => 'Mortality',
        self::INV => 'Invisibility',
    ];

    public function getPowerCode(string $powerName) {
        
        $powerCode = array_search($powerName, self::POWER_NAME);
        if($powerCode === false) {
            throw new InvalidArgumentException(\sprintf('%s is not a power name valid', $powerName));
        }
        return $powerCode;
    }
}
