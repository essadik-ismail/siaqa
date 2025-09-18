{{-- Private Image Helper --}}
@php
    use App\Helpers\ImageHelper;
@endphp

@if(isset($model) && isset($type))
    @php
        $imageUrl = ImageHelper::getModelImageUrl($model, $type);
    @endphp
    
    @if($imageUrl)
        <img src="{{ $imageUrl }}" 
             alt="{{ $alt ?? $model->name ?? $model->nom ?? 'Image' }}" 
             class="{{ $class ?? '' }}"
             style="{{ $style ?? '' }}"
             loading="lazy">
    @else
        @if(isset($placeholder))
            <div class="{{ $placeholderClass ?? 'bg-gray-200 flex items-center justify-center text-gray-500' }}">
                {{ $placeholder }}
            </div>
        @endif
    @endif
@elseif(isset($imagePath) && isset($type) && isset($id))
    @php
        $imageUrl = ImageHelper::getPrivateImageUrl($type, $id);
    @endphp
    
    @if($imageUrl)
        <img src="{{ $imageUrl }}" 
             alt="{{ $alt ?? 'Image' }}" 
             class="{{ $class ?? '' }}"
             style="{{ $style ?? '' }}"
             loading="lazy">
    @else
        @if(isset($placeholder))
            <div class="{{ $placeholderClass ?? 'bg-gray-200 flex items-center justify-center text-gray-500' }}">
                {{ $placeholder }}
            </div>
        @endif
    @endif
@endif
