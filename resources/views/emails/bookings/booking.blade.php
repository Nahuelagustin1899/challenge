@extends( 'emails.template.main')
@section( 'content')
    <tr>
        <td>
            <h3>¡Hola, {{ $booking->customer_name }}!</h3>
        </td>
    </tr>
    <tr>
        <td>
        <p>Te informamos que recibimos tu reserva.</p>
        </td>
    </tr>
    <tr>
        <td>
            <br>
            <p>Aca están los detalles de tu reserva:</p>
            <p>Nombre del hotel: <b>{{ $booking->hotel->name }}</b></p>
            <p>Nombre del tour: <b>{{ $booking->tour->name }}</b></p>
            <p>Fecha de reserva: <b>{{ $booking->booking_date }}</b></p>
            <p>Número de personas: <b>{{ $booking->number_of_people }}</b></p>
        </td>
    </tr>
@endsection
