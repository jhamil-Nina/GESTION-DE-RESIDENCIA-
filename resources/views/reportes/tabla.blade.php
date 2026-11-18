<table>
    <thead>
        <tr>
            @foreach($columnas as $columna)
                <th scope="col">{{ $columna }}</th>
            @endforeach
        </tr>
    </thead>
    <tbody>
        @forelse($registros as $registro)
            <tr>
                @foreach($fila($registro) as $celda)
                    <td>{{ $celda }}</td>
                @endforeach
            </tr>
        @empty
            <tr><td colspan="{{ count($columnas) }}">No hay resultados para este reporte.</td></tr>
        @endforelse
    </tbody>
</table>
