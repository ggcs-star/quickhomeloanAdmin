@extends('layouts.admin')

@section('title','Settings')

@section('content')
<div x-data="settingsManager()" class="space-y-6">

    {{-- HEADER --}}
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-900">Settings</h1>
        <p class="text-sm text-gray-500">
            Manage your account and application preferences
        </p>
    </div>

    <div class="bg-white rounded-xl shadow flex flex-col lg:flex-row overflow-hidden">

        {{-- SIDEBAR --}}
        <aside class="w-full lg:w-1/4 border-b lg:border-b-0 lg:border-r">
            <nav class="p-3 sm:p-4 space-y-1 overflow-x-auto lg:overflow-visible
                        flex lg:block gap-2 lg:gap-0">

                <template x-for="tab in tabs" :key="tab">
                    <button
                        @click="activeTab = tab"
                        class="flex-shrink-0 lg:w-full
                               flex items-center justify-between
                               px-4 py-3 text-sm font-medium rounded-md
                               whitespace-nowrap"
                        :class="activeTab === tab
                            ? 'bg-indigo-50 text-indigo-700'
                            : 'text-gray-600 hover:bg-gray-50'">
                        <span x-text="tab"></span>
                        <span class="hidden lg:inline">›</span>
                    </button>
                </template>

            </nav>
        </aside>

        {{-- CONTENT --}}
        <main class="flex-1 p-4 sm:p-6">

            {{-- PROFILE --}}
            <div x-show="activeTab === 'Profile'" x-cloak>
                <h3 class="text-lg sm:text-xl font-bold">Profile</h3>
                <p class="text-gray-500 mt-1">Update your personal information.</p>

                <form class="mt-6 space-y-4 max-w-2xl">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <input x-model="profile.firstName"
                               placeholder="First Name"
                               class="border rounded-lg px-3 py-2 w-full">
                        <input x-model="profile.lastName"
                               placeholder="Last Name"
                               class="border rounded-lg px-3 py-2 w-full">
                    </div>

                    <input x-model="profile.email"
                           placeholder="Email"
                           class="border rounded-lg px-3 py-2 w-full">

                    <input x-model="profile.phone"
                           placeholder="Phone"
                           class="border rounded-lg px-3 py-2 w-full">

                    <div class="flex justify-end">
                        <button type="button"
                                @click="save('Profile')"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- NOTIFICATIONS --}}
            <div x-show="activeTab === 'Notifications'" x-cloak>
                <h3 class="text-lg sm:text-xl font-bold">Notifications</h3>
                <p class="text-gray-500 mt-1">Manage how you receive notifications.</p>

                <div class="mt-6 space-y-4 max-w-xl">
                    <template x-for="(value, key) in notifications" :key="key">
                        <div class="flex justify-between items-center gap-4 border-b pb-3">
                            <span class="text-sm capitalize"
                                  x-text="key.replace(/([A-Z])/g,' $1')"></span>

                            <button @click="notifications[key] = !notifications[key]"
                                    class="w-14 h-8 rounded-full relative transition flex-shrink-0"
                                    :class="notifications[key] ? 'bg-indigo-600' : 'bg-gray-300'">
                                <span class="absolute top-1 left-1 w-6 h-6 bg-white rounded-full transition"
                                      :class="notifications[key] ? 'translate-x-6' : ''"></span>
                            </button>
                        </div>
                    </template>
                </div>
            </div>

            {{-- SECURITY --}}
            <div x-show="activeTab === 'Security'" x-cloak>
                <h3 class="text-lg sm:text-xl font-bold">Security</h3>
                <p class="text-gray-500 mt-1">Change your password.</p>

                <form class="mt-6 space-y-4 max-w-md">
                    <input x-model="security.current" type="password"
                           placeholder="Current Password"
                           class="border rounded-lg px-3 py-2 w-full">
                    <input x-model="security.new" type="password"
                           placeholder="New Password"
                           class="border rounded-lg px-3 py-2 w-full">
                    <input x-model="security.confirm" type="password"
                           placeholder="Confirm Password"
                           class="border rounded-lg px-3 py-2 w-full">

                    <div class="flex justify-end">
                        <button type="button"
                                @click="save('Security')"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>

            {{-- ORGANIZATION --}}
            <div x-show="activeTab === 'Organization'" x-cloak>
                <h3 class="text-lg sm:text-xl font-bold">Organization</h3>
                <p class="text-gray-500 mt-1">Manage company details.</p>

                <form class="mt-6 space-y-4 max-w-md">
                    <input x-model="organization.name"
                           placeholder="Organization Name"
                           class="border rounded-lg px-3 py-2 w-full">
                    <input x-model="organization.email"
                           placeholder="Organization Email"
                           class="border rounded-lg px-3 py-2 w-full">
                    <input x-model="organization.address"
                           placeholder="Address"
                           class="border rounded-lg px-3 py-2 w-full">

                    <div class="flex justify-end">
                        <button type="button"
                                @click="save('Organization')"
                                class="bg-indigo-600 text-white px-6 py-2 rounded-md">
                            Save Organization
                        </button>
                    </div>
                </form>
            </div>

        </main>
    </div>
</div>

{{-- Alpine --}}
<script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

<script>
function settingsManager() {
    return {
        activeTab: 'Profile',
        tabs: ['Profile','Notifications','Security','Organization'],

        profile: {
            firstName: 'Admin',
            lastName: 'User',
            email: 'admin@example.com',
            phone: '+91 99999 00000'
        },

        notifications: {
            email: true,
            sms: false,
            leadAssignment: true,
            documentUpload: true
        },

        security: {
            current: '',
            new: '',
            confirm: ''
        },

        organization: {
            name: 'FinTech Solutions Pvt Ltd',
            email: 'contact@fintech.com',
            address: 'Mumbai, India'
        },

        save(section) {
            alert(section + ' updated successfully!');
        }
    }
}
</script>
@endsection
