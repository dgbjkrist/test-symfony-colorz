<?php

namespace App\Service;
use App\Enum\PowerEnum;
use App\Exception\InvalidArgumentException;
use App\Exception\UnexpectedException;

class TeamHelper
{
    private const TEAMS_HEADER = ['Squad Name', 'Home Town', 'Formed Year', 'Base', 'Number of members', 'Average Age', 'Average strengh of team', 'Is Active'];

    private const MEMBERS_INCOMPLETE_HEADER = ['Squad Name', 'Home Town', 'Name', 'Secret ID', 'Age', 'Number of Power'];
    
    private const TEAM_FILE = "teams.csv";

    private const MEMBERS_FILE = "team_members.csv";

    private $fileUploader;
    private $powerEnum;

    public function __construct(FileUploader $fileUploader, PowerEnum $powerEnum) {
        $this->fileUploader = $fileUploader;
        $this->powerEnum = $powerEnum;
    }

    
    public function buildTeams(array $datas)
    {
        $this->keyExists('teams', $datas);

        $teamsReconstitutedForCSV = [];
        $membersReconstitutedForCSV = [];

        $highestPower = 0;
        
        foreach($datas["teams"] as $team) {
            $this->keyExists(['members', 'squadName', 'homeTown', 'formed', 'secretBase', 'active'], $team);
            
            $members = $team['members'];
            $numberMembers = count($team['members']);
            $teamReconstitutedForCSV = [
                $team['squadName'],
                $team['homeTown'],
                $team['formed'],
                $team['secretBase'],
                $numberMembers,
                $this->getSumAgesTeam($members)/$numberMembers,
                $this->getsumPowersTeam($members)/$numberMembers,
                $team['active']
            ];

            foreach($team['members'] as $member) {
                $this->keyExists(['name', 'secretIdentity', 'age', 'powers'], $member);
                
                $powersMemberReconstitutedForCSV = [];

                if(is_array($member['powers'])) {
                    $powersMemberReconstitutedForCSV = $this->getPowersCodes($member);
                }
                
                $numberOfPower = count($powersMemberReconstitutedForCSV);
                $infosMemberReconstitutedForCSV = [
                    $team['squadName'],
                    $team['homeTown'],
                    $member['name'],
                    $member['secretIdentity'],
                    $member['age'],
                    $numberOfPower
                ];

                $membersReconstitutedForCSV[] = array_merge($infosMemberReconstitutedForCSV, $powersMemberReconstitutedForCSV);

                $highestPower = max($highestPower, $numberOfPower);
            }

            $teamsReconstitutedForCSV[] = $teamReconstitutedForCSV;
        }

        $headerOfPower = $this->getHeaderOfPower($highestPower);

        $membersFileHeader = array_merge(self::MEMBERS_INCOMPLETE_HEADER, $headerOfPower);
        
        $this->buildFileCSV(self::TEAM_FILE, self::TEAMS_HEADER, $teamsReconstitutedForCSV);
        $this->buildFileCSV(self::MEMBERS_FILE, $membersFileHeader, $membersReconstitutedForCSV);
    }

    public function buildFileCSV(string $fileName, array $fileCSVHeader, array $datas)
    {
        try {
            $fileCSV = $this->fileUploader->upload($fileName);

            if (!fputcsv($fileCSV, $fileCSVHeader)) {
                throw new UnexpectedException("Error Processing Request : ".__METHOD__);
            }
        
            foreach ($datas as $data) {
                if (!fputcsv($fileCSV, $data)) {
                    throw new UnexpectedException("Error Processing Request : ".__METHOD__);
                }
            }

            fclose($fileCSV);

        } catch (\Throwable $th) {
            throw new UnexpectedException($th->getMessage());
        }
    }

    public function keyExists($needle, array $haystack)
    {
        if (\is_array($needle)) {
            foreach ($needle as $value) {
                if (false === array_key_exists($value, $haystack)) {
                    throw new InvalidArgumentException(\sprintf('key %s should exist, please check format file', $value));
                }
            }
        } else {
            if (false === array_key_exists($needle, $haystack)) {
                throw new InvalidArgumentException(\sprintf('key %s should exist, please check format file', $needle));
            }
        }
    }

    private function getHeaderOfPower($highestPower) {
        $headerOfPower = [];
        for ($i=1; $i <= $highestPower; $i++) { 
            $headerOfPower[] = sprintf('Power%d', $i);
        }

        return $headerOfPower;
    }

    private function getPowersCodes($member) {
        $powerCode = [];

        if(is_array($member['powers'])) {
            foreach($member['powers'] as $power) {
                $powerCode[] = $this->powerEnum->getPowerCode($power);
            }
        }

        return $powerCode;
    }

    private function getSumAgesTeam(array $members)
    {
        $sumAgesTeam = 0;

        foreach($members as $member) {
            $sumAgesTeam += $member['age'];
        }
        
        return $sumAgesTeam; 
    }

    private function getsumPowersTeam(array $members)
    {
        $sumPowersTeam = 0;

        foreach($members as $member) {
            if (!is_array($member['powers'])) {
                continue;
            }
            $sumPowersTeam += count($member['powers']);
        }
        
        return $sumPowersTeam; 
    }
}
