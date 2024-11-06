<x-app-layout>
    <div class="min-h-screen flex flex-col items-center pt-6 bg-gray-100 dark:bg-gray-900">
        <div class="w-full sm:max-w-2xl bg-white dark:bg-gray-800 shadow-xl rounded-lg overflow-hidden">

            <!-- Encabezado de Ticket -->
            <div class="bg-indigo-600 text-white p-6 rounded-t-lg">
                <h1 class="text-2xl font-extrabold">{{ $ticket->title }}</h1>
                <p class="text-sm mt-2">Creado por <span class="font-semibold">{{ $ticket->user->name }}</span> - {{ $ticket->created_at->format('d M, Y') }}</p>
            </div>

            <!-- Contenido del Ticket -->
            <div class="p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Descripción</h3>
                    <p class="mt-2 text-gray-800 dark:text-gray-300">{{ $ticket->description }}</p>
                </div>

                @if ($ticket->attachment)
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Archivo Adjunto</h3>
                        <a href="{{ asset('storage/' . $ticket->attachment) }}" target="_blank" class="text-blue-500 hover:text-blue-700 underline mt-2 inline-block">Ver Archivo</a>
                    </div>
                @endif

                <div>
                    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-200">Estado</h3>
                    <div class="mt-2">
                        @if (auth()->user()->is_admin)
                            <form action="{{ route('ticket.update', $ticket->id) }}" method="post" class="flex space-x-2">
                                @csrf
                                @method('patch')
                                <button name="status" value="Abierto" class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 font-semibold">Abierto</button>
                                <button name="status" value="Resuelto" class="px-4 py-2 bg-yellow-500 text-white rounded-md hover:bg-yellow-600 font-semibold">Resuelto</button>
                                <button name="status" value="Rechazado" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 font-semibold">Rechazado</button>
                            </form>
                        @else
                            <span class="px-2 py-1 bg-gray-200 dark:bg-gray-700 text-gray-900 dark:text-gray-100 rounded">{{ $ticket->status }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Sección de Respuestas -->
            <div class="p-6 border-t border-gray-200 dark:border-gray-700">
                <h2 class="text-xl font-bold text-gray-700 dark:text-gray-200 mb-4">Respuestas o Comentarios</h2>
                @foreach ($responses as $response)
                    <div class="mb-4 p-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                        <p class="font-semibold text-gray-800 dark:text-gray-100">{{ $response->user->name }} <span class="text-sm text-gray-600 dark:text-gray-400">({{ $response->created_at->diffForHumans() }})</span></p>
                        <p class="mt-2 text-gray-700 dark:text-gray-300">{{ $response->response }}</p>
                    </div>
                @endforeach

                @auth
                    <form method="POST" action="{{ route('responses.store', $ticket) }}" class="mt-4">
                        @csrf
                        <div class="mb-4">
                            <x-input-label for="response" :value="__('Respuesta')" />
                            <textarea id="response" name="response" class="block mt-1 w-full p-3 border border-gray-300 rounded-lg focus:ring-indigo-500" required></textarea>
                        </div>
                        <div class="flex items-center justify-end">
                            <x-primary-button>
                                {{ __('Agregar Respuesta') }}
                            </x-primary-button>
                        </div>
                    </form>
                @endauth
            </div>

            <!-- Botones de Acción -->
            <div class="p-6 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 flex justify-end space-x-4 rounded-b-lg">
                <a href="{{ route('ticket.edit', $ticket->id) }}" class="px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 font-semibold">Editar</a>
                <form action="{{ route('ticket.destroy', $ticket->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que quieres eliminar este ticket?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-md hover:bg-red-600 font-semibold">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
