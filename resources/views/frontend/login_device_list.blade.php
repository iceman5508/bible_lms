<div class="table-responsive">
    <table class="table bg-white my-courses-page-table">
        <thead>
            <tr>
                <th scope="col" class="color-gray font-15 font-medium">{{__('OS')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Browser')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('IP address')}}</th>
                <th scope="col" class="color-gray font-15 font-medium">{{__('Last session')}}</th>
                <th scope="col" class="color-gray font-15 font-medium text-end">{{__('Action')}}</th>
            </tr>
        </thead>
        <tbody>

            @foreach($devices as $device)
            @php
            $data = $device->data;
            $device_uuid = request()->cookie('_uuid_d');
            $browser = explode('|', $device->device_type)[1];
            @endphp
            <tr>
                <td class="wishlist-price font-15 color-heading">{{ $data['platform_name'] }}<span class="text-info">{{ (($device_uuid == $device->device_uuid) ? ' ('.__('current').')' : '') }}</span></td>
                <td class="wishlist-price font-15 color-heading">{{ $browser }}</td>
                <td class="wishlist-price font-15 color-heading">{{ $device->ip }}</td>
                <td class="wishlist-price font-15 color-heading"><span class="session-online"></span>{{ $device->updated_at }}</td>
                <td class="wishlist-add-to-cart-btn text-end">
                    <a href="#" class="theme-button theme-button1 theme-button3 font-13" onclick="event.preventDefault(); document.getElementById('logout-device-{{ $device->id }}').submit();">
                        {{ __('Logout') }}
                    </a>
                    <form id="logout-device-{{ $device->id }}" action="{{ route('student.logout_device', $device->id) }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                    </form>
                </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>