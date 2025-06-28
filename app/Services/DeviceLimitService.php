<?php

namespace App\Services;

use App\Models\User;
use App\Models\UserDevice;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Jenssegers\Agent\Facades\Agent;

class DeviceLimitService
{
    /**
     * Create a new class instance.
     */
    public function registerDevice(User $user) {
			$deviceInfo = $this->getDeviceInfo();
			$existingDevice = $this->findExistingDevice($user, $deviceInfo);

			if($existingDevice) {
				$existingDevice->update(['last_active' => now()]);
				Session::put('device_id', $existingDevice->device_id);
				return $existingDevice;
			}

			if($this->hasReachedDeviceLimit($user)) {
				return false;
			}

			$device = $this->createNewDevice($user, $deviceInfo);
			session('device_id', $device->device_id);
			return $device;
	 }

	 public function logoutDevice($deviceId) {
			UserDevice::where('device_id', $deviceId)->delete();
			session()->forget('device_id');
	 }

	 private function getDeviceInfo() {
		return [
			'device_name' => $this->generateDeviceName(),
			'device_type' => Agent::isDekstop() ? 'dekstop' : (Agent::isPhone() ? 'phone' : 'tablet'),
			'platform' => Agent::platform(),
			'platform_version' => Agent::version(Agent::platform()),
			'browser' => Agent::browser(),
			'browser_version' => Agent::version(Agent::browser())
		];
	 }

	 private function generateDeviceName() {
		return ucfirst(Agent::platform()) . " " . ucfirst(Agent::browser());
	 }

	 private function findExistingDevice(User $user, array $deviceInfo) {
		return UserDevice::where('user_id', $user->id)
		->where('device_type', $deviceInfo['device_type'])
		->where('platform', $deviceInfo['platform'])
		->where('browser', $deviceInfo['browser'])
		->first();
	 }

	 private function hasReachedDeviceLimit(User $user) {
		// dd($user->getCurrentPlan());
		$maxDevices = $user->getCurrentPlan()->max_devices ?? 1;
		return UserDevice::where('user_id', $user->id)->count() >= $maxDevices;
	 }

	 private function createNewDevice(User $user, array $deviceInfo) {
		$deviceId = Str::random(32);
		// session('device_id', $deviceId);
		return UserDevice::create([
			'user_id' => $user->id,
			'device_name' => $deviceInfo['device_name'],
			'device_id' => $deviceId,
			'device_type' => $deviceInfo['device_type'],
			'platform' => $deviceInfo['platform'],
			'platform_version' => $deviceInfo['platform_version'],
			'browser' => $deviceInfo['browser'],
			'browser_version' => $deviceInfo['browser_version'],
			'last_active' => now()
		]);
	}
}
