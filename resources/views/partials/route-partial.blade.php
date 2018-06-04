<?php
    $authRequired = false;
?>
<div class="route {{ $route->methods[0] }}">
    <div class="cf">
        <div class="method">{{ $route->methods[0] }}</div>
        <div class="uri">{{ $route->uri }}</div>
        <div class="middlewares">
            @if (is_array($route->action['middleware']))
                @foreach ($route->action['middleware'] as $middleware)
                    @if (!in_array($middleware, ['web', 'api']))
                        <div class="middleware-tag" title="{{ $middleware }} middleware">{{ $middleware }}</div>
                        <?php $authRequired = true; ?>
                    @endif
                @endforeach
            @elseif (!in_array($route->action['middleware'], ['web', 'api']))
                <div class="middleware-tag" title="{{ $route->action['middleware'] }} middleware">{{ $route->action['middleware'] }}</div>
                <?php $authRequired = true; ?>
            @endif

            @if ($authRequired)
                <i class="fa fa-lock" aria-hidden="true" title="Behind Authentication"></i>
            @endif
        </div>
    </div>

    <div class="route-dropdown">
        <div class="cf">
            <div class="detail-title">URL</div>
            <div class="detail-value">
                <a href="{{ env('APP_URL') }}/{{ $route->uri === '/' ? null : $route->uri }}" target="_blank">{{ env('APP_URL') }}/{{ $route->uri === '/' ? null : $route->uri }}</a>
            </div>
        </div>
        <div class="cf">
            <div class="detail-title">NAME</div>
            <div class="detail-value">
            @if (isset($route->action['as']))
                {{ $route->action['as'] }}
            @else
                -
            @endif
            </div>
        </div>
    </div>
</div>