<?php

namespace App\Service;
use App\Enum\PowerEnum;

class TeamHelper
{
    private $header = ['Squad Name', 'Home Town', 'Formed Year', 'Base', 'Number of members', 'Average Age', 'Average strengh of team', 'Is Active'];

    private $headerMembers = ['Squad Name', 'Name', 'Secret ID', 'Age', 'Number of Power', 'Average strengh of team'];
    
    private $fileUploader;
    private $powerEnum;

    public function __construct(FileUploader $fileUploader, PowerEnum $powerEnum) {
        $this->fileUploader = $fileUploader;
        $this->powerEnum = $powerEnum;
    }
    public function buildTeams(array $datas)
    {
        $fp = fopen($this->fileUploader->getTargetDirectory().'teams.csv', 'w');
        fputcsv($fp, $this->getTeamsHeader());

        foreach($datas["teams"] as $team) {
            $members = $team['members'];
            $numberMembers = count($team['members']);
            $row = [
                $team['squadName'],
                $team['homeTown'],
                $team['formed'],
                $team['secretBase'],
                $numberMembers,
                $this->getSumAgesTeam($members)/$numberMembers,
                $this->getsumPowersTeam($members)/$numberMembers,
                $team['active']
            ];

            fputcsv($fp, $row);
        }
        
        return $fp;
    }

    public function buildMembersTeams(array $datas)
    {
        $membersFile = fopen($this->fileUploader->getTargetDirectory().'team_members.csv', 'w');
        // fputcsv($membersFile, $this->getTeamsMembersHeader());

        $members = [];
        $highestPower = 0;

        foreach($datas["teams"] as $team) {
            foreach($team['members'] as $member) {
                
                $arrayPowers = [];
                if(is_array($member['powers'])) {
                    $arrayPowers = $this->getPowers($member);
                }
                
                $numberOfPower = count($arrayPowers);
                $member = [
                    $team['squadName'],
                    $team['homeTown'],
                    $member['name'],
                    $member['secretIdentity'],
                    $member['age'],
                    $numberOfPower
                ];
                $member = array_merge($member, $arrayPowers);
                $members[] = $member;

                $highestPower = max($highestPower, $numberOfPower);
            }
        }

        $headerOfPower = $this->getHeaderOfPower($highestPower);

        $membersFileHeader = array_merge($this->getTeamsMembersHeader(), $headerOfPower);

        // array_push($headerOfPower, $membersFileHeader);
        // $members[] = $membersFileHeader;

        // dd($headerOfPower)
        array_unshift($members, $membersFileHeader);

        foreach ($members as $member) {
            fputcsv($membersFile, $member);
        }

        fclose($membersFile);
    }

    private function getHeaderOfPower($highestPower) {
        $headerOfPower = [];
        for ($i=1; $i <= $highestPower; $i++) { 
            $headerOfPower[] = sprintf('Power%d', $i);
        }

        return $headerOfPower;
    }

    private function getPowers($member) {
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
    
    public function getTeamsHeader()
    {
        return $this->header;
    }

    public function getTeamsMembersHeader()
    {
        return $this->headerMembers;
    }
}
