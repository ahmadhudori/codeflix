<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Jenssegers\Agent\Facades\Agent;
use Illuminate\Support\Str;

class DeviceLimitService
{
    
	public function registerDevice(User $user)
	{
		$deviceInfo = $this->getDeviceInfo();

		$existingDevices = $this->findExistingDevices($user, $deviceInfo);

		if ($existingDevices) {
			$existingDevices->update(['last_active' => now()]);
			session(['device_id' => $existingDevices->device_id]);
			return $existingDevices;
		}

		if ($this->hasReachedDeviceLimit($user)) {
			return false;
		}

		$device = $this->createNewDevice($user, $deviceInfo);
		session(['device_id' => $device->device_id]);
		return $device;
	}

	public function getDeviceInfo()
	{
		return [
			'device_name' => $this->generateDeviceName(),
			'device_type' => Agent::isDekstop() ? 'Dekstop' : (Agent::isPhone() ? 'Phone' : 'Tablet'),
			'platform' => Agent::platform(),
			'platform_version' => Agent::version(Agent::platform()),
			'browser' => Agent::browser(),
			'browser_version' => Agent::version(Agent::browser()),
		];
	}

	public function generateDeviceName()
	{
		return ucfirst(Agent::platform()) . ' ' . ucfirst(Agent::browser());
	}

	public function findExistingDevices(User $user, array $deviceInfo)
	{
		return UserDevice::where('user_id', $user->id)
		->where('device_type', $deviceInfo['device_type'])
		->where('platform', $deviceInfo['platform'])
		->where('browser', $deviceInfo['browser'])
		->first();
	}

	public function hasReachedDeviceLimit(User $user)
	{
		$maxDevices = $user->getCurrentPlan()->max_devices ?? 1;
		return UserDevice::where('user_id', $user->id)->count() >= $maxDevices;
	}

	public function createNewDevice(User $user, array $deviceInfo)
	{
		$device_id = Str::random(32);
		return UserDevice::create([
			'user_id' => $user->id,
			'device_name' => $deviceInfo['device_name'],
			'device_id' => $device_id,
			'device_type' => $deviceInfo['device_type'],
			'platform' => $deviceInfo['platform'],
			'platform_version' => $deviceInfo['platform_version'],
			'browser' => $deviceInfo['browser'],
			'browser_version' => $deviceInfo['browser_version'],
			'last_active' => now(),
		]);
	}
}
