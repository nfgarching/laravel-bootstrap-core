{{-- x-tags: button.model --}}
@props([
    'model' => '',
    'modid' => '',
    'type' => 'primary',
])

@php
    $disabled = 'disabled';
    $routeName = '';
    $routeUrl = '#'; // Fallback-URL, falls die Route nicht existiert
    // dump($model::getShowRouteName());
    $modelName = Str::lower(class_basename($model)); // z.B. "Document", "Name", "File"
    switch ($modelName) {
        case 'dokument':
            $routeName = 'dokumente';
            break;

        default:
            $routeName = Str::plural($modelName); // z.B. "documents", "names", "files"
            break;
    }
    // Prüfe ob die Methode im Model existiert
    if (class_exists($model) && method_exists($model, 'getShowRouteName')) {
        $disabled = '';
        $routeName = $model::getShowRouteName();
        $routeUrl = route($routeName, $modid);
    }
    // Alt. Prüfe ob die Route abgeleitet vom Modelname existiert
    elseif (Route::has($routeName . '.show')) {
        // Extrahiere den Model-Namen aus dem vollen Klassennamen
        $disabled = '';
        $routeUrl = route($routeName . '.show', $modid);
    }
    // dd( $routeUrl );
@endphp

<x-button.link :class="$disabled" :href="$routeUrl">
    {{ class_basename($model) }}
</x-button.link>
