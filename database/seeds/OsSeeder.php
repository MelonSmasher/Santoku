<?php

use Illuminate\Database\Seeder;
use App\OperatingSystem;

class OsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */

    public $linux = [
        'Ubuntu' => [
            'Disco Dingo' => '19.04',
            'Cosmic Cuttlefish' => '18.10',
            'Bionic Beaver' => '18.04',
            'Artful Aardvark' => '17.10',
            'Zesty Zapus' => '17.04',
            'Yakkety Yak' => '16.10',
            'Xenial Xerus' => '16.04',
            'Wily Werewolf' => '15.10',
            'Vivid Vervet' => '15.04',
            'Utopic Unicorn' => '14.10',
            'Trusty Tahr' => '14.04'
        ],
        'RedHat' => [
            'Maipo' => '7',
            'Santiago' => '6'
        ],
        'CentOS' => [
            'Maipo' => '7',
            'Santiago' => '6'
        ]
    ];

    public $windows = [
        'Server 2019' => '10.x',
        'Server 2016' => '10.x',
        'Server 2012 R2' => '6.3',
        'Server 2012' => '6.2',
        'Server 2008 R2' => '6.1',
        '10' => '10.x',
        '8.1' => '6.3',
        '8' => '6.2',
        '7' => '6.1'
    ];

    public function run()
    {
        foreach ($this->windows as $name => $ver) {
            OperatingSystem::create([
                'name' => 'Windows ' . $name,
                'version' => $ver,
                'codename' => null,
                'logo' => 'fab fa-windows',
                'platform' => 'windows'
            ]);
        }

        foreach ($this->linux as $distro => $versions) {
            foreach ($versions as $codeName => $ver) {
                if ($distro == 'Ubuntu') {
                    OperatingSystem::create([
                        'name' => $distro . ' Linux ' . $ver,
                        'version' => $ver,
                        'codename' => $codeName,
                        'logo' => 'fl-ubuntu',
                        'platform' => 'linux'
                    ]);
                } else if ($distro == 'Debian') {
                    OperatingSystem::create([
                        'name' => $distro . ' Linux ' . $ver,
                        'version' => $ver,
                        'codename' => $codeName,
                        'logo' => 'fl-debian',
                        'platform' => 'linux'
                    ]);
                } else if ($distro == 'RedHat') {
                    OperatingSystem::create([
                        'name' => $distro . ' Enterprise Linux ' . $ver,
                        'version' => $ver,
                        'codename' => $codeName,
                        'logo' => 'fl-redhat',
                        'platform' => 'linux'
                    ]);
                } else if ($distro == 'CentOS') {
                    OperatingSystem::create([
                        'name' => $distro . ' Linux ' . $ver,
                        'version' => $ver,
                        'codename' => $codeName,
                        'logo' => 'fl-centos',
                        'platform' => 'linux'
                    ]);
                } else {
                    OperatingSystem::create([
                        'name' => $distro . ' Linux ' . $ver,
                        'version' => $ver,
                        'codename' => $codeName,
                        'logo' => 'fab fa-linux',
                        'platform' => 'linux'
                    ]);
                }
            }
        }
    }
}
