<?php

namespace Rmsramos\Activitylog\Infolists\Components;

use Carbon\Carbon;
use Filament\Forms\Components\Concerns\CanAllowHtml;
use Filament\Infolists\Components\Entry;
use Filament\Support\Concerns\HasExtraAttributes;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Str;
use Rmsramos\Activitylog\Infolists\Concerns\HasModifyState;

class TimeLineTitleEntry extends Entry
{
    use CanAllowHtml;
    use HasExtraAttributes;
    use HasModifyState;

    protected function setUp(): void
    {
        parent::setUp();

        $this->configureTitleEntry();
    }

    protected string $view = 'activitylog::filament.infolists.components.time-line-title-entry';

    private function configureTitleEntry()
    {
        $this
            ->hiddenLabel()
            ->modifyState(fn ($state) => $this->modifiedTitle($state));
    }

    private function modifiedTitle($state): string|HtmlString
    {
        if ($state['description'] == $state['event']) {
            $className  = Str::lower(Str::snake(class_basename($state['subject']), ' '));
            $causerName = $this->getCauserName($state['causer']);
            $update_at  = Carbon::parse($state['update'])->translatedFormat(config('filament-activitylog.datetime_format'));

            return new HtmlString(
                sprintf(
                    'The <strong>%s</strong> was <strong>%s</strong> by <strong>%s</strong>. <br><small> Updated at: <strong>%s</strong></small>',
                    $className,
                    $state['event'],
                    $causerName,
                    $update_at
                )
            );
        }

        return '';
    }

}
