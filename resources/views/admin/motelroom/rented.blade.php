@extends('admin.layout.master')

@section('content3')
    <h1>Danh sách phòng đã thuê</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Tiêu đề</th>
                <th>Địa chỉ</th>
                <th>Hình ảnh</th>
                <th>Số điện thoại</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rented_rooms as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->title }}</td>
                    <td>{{ $room->address }}</td>
                    <td><img src="{{ $room->images[0] }}" alt="{{ $room->title }}" width="100"></td>
                    <td>{{ $room->phone }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
