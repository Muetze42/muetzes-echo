<?php

namespace App\Nova;

use Illuminate\Http\Request;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use NovaItemsField\Items;

class Command extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var string
     */
    public static $model = \App\Models\Command::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'command';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'command',
        'content',
    ];

    /**
     * Build an "index" query for the given resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function indexQuery(NovaRequest $request, $query)
    {
        if ($request->user()->is_admin) {
            return $query;
        }
        return $query->whereHas('bot', function ($query) use ($request) {
            $query->where('user_id', $request->user()->id);
        });
    }

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Text::make(__('Command'), 'command')
                ->rules('required'),
            Text::make(__('Run command'), 'command', function () {
                return '<small>'.htmlspecialchars($this->bot->prefix.$this->command).'<br>'.htmlspecialchars($this->bot->prefix.' '.$this->command).'</small>';
            })
                ->sortable()->exceptOnForms()->asHtml(),
            Textarea::make(__('Content'), 'content')
                ->rules('required')->alwaysShow()->hideFromIndex(),
            Text::make(__('Content'), 'content', function () {
                return htmlspecialchars($this->content);
            })
                ->sortable()->onlyOnIndex()->asHtml(),
            Boolean::make(__('Protected'), 'is_protected')
                ->help(__('Protected commands can be executed only by the bot owner'))->sortable(),
            Items::make(__('Channels'), 'channels')->hideFromIndex(),
            Text::make(__('Channels'), 'channels', function () {
                return is_array($this->channels) ? implode(', ', $this->channels) : '-';
            })->sortable()->asHtml()->onlyOnIndex(),
            BelongsTo::make(__('Bot'), 'bot', Bot::class)->withoutTrashed(),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function cards(Request $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function lenses(Request $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [];
    }
}
