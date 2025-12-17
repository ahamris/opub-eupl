<?php

namespace App\View\Components\UI;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DatePicker extends Component
{
    public string $classes;

    public string $inputId = '';

    public array $flatpickrOptions = [];

    public function __construct(
        public string $label = '',
        public string $name = '',
        public ?string $id = null,
        public ?string $value = null,
        public string $type = 'date',
        public string $placeholder = '',
        public string $hint = '',
        public bool $error = false,
        public string $errorMessage = '',
        public bool $required = false,
        public bool $disabled = false,
        public bool $readonly = false,
        public ?string $icon = null,
        public string $iconPosition = 'left',
        public ?string $size = null,
        public ?string $minDate = null,
        public ?string $maxDate = null,
        public ?string $format = null,
        public ?string $defaultDate = null,
        public bool $enableTime = false,
        public bool $time24hr = false,
        public ?string $timeFormat = null,
        public ?string $theme = null,
        public ?string $locale = null,
        public bool $altInput = false,
        public ?string $altFormat = null,
        public bool $allowInput = true,
        public bool $clickOpens = true,
        public bool $inline = false,
        public bool $static = false,
        public bool $weekNumbers = false,
        public bool $disableMobile = false,
        public bool $enableSeconds = false,
        public bool $noCalendar = false,
        public ?int $hourIncrement = null,
        public ?int $minuteIncrement = null,
        public ?int $defaultHour = null,
        public ?int $defaultMinute = null,
        public ?string $maxTime = null,
        public ?string $minTime = null,
        public ?array $disable = null,
        public ?array $enable = null,
        public ?string $position = null,
        public array $options = []
    ) {
        $this->inputId = $id ?? ($name ?: 'datepicker-'.uniqid());

        $classes = [];
        $baseClasses = 'block w-full border rounded-md bg-white dark:bg-zinc-800 text-zinc-900 dark:text-zinc-100 placeholder:text-zinc-500 dark:placeholder:text-zinc-400 transition-all duration-200 border-zinc-300 dark:border-zinc-700 focus:outline-none';
        $classes[] = $baseClasses;

        // Size classes - Input component ile aynı
        if ($size === 'sm') {
            $classes[] = 'px-3 py-1.5 text-sm leading-5 tracking-[0.25px]';
        } elseif ($size === 'lg') {
            $classes[] = 'px-5 py-3 text-base leading-6 tracking-[0.5px]';
        } else {
            $classes[] = 'px-4 py-2.5 text-base leading-6 tracking-[0.5px]';
        }

        if ($error) {
            $classes[] = 'bg-red-50 dark:bg-red-900/20 border-red-500 dark:border-red-400 text-red-600 dark:text-red-400 placeholder:text-red-400 dark:placeholder:text-red-500';
        } elseif ($disabled) {
            $classes[] = 'bg-zinc-100 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700 text-zinc-500 dark:text-zinc-500 cursor-not-allowed';
        } elseif ($readonly) {
            $classes[] = 'bg-zinc-50 dark:bg-zinc-800 border-zinc-300 dark:border-zinc-700';
        }

        if ($icon && $iconPosition === 'left') {
            $classes[] = 'pl-11';
        } elseif ($icon && $iconPosition === 'right') {
            $classes[] = 'pr-11';
        }

        $this->classes = implode(' ', $classes);

        $this->buildFlatpickrOptions();
    }

    protected function buildFlatpickrOptions(): void
    {
        $options = [];

        $mode = match ($this->type) {
            'range' => 'range',
            'multiple' => 'multiple',
            default => 'single',
        };
        $options['mode'] = $mode;

        // Handle noCalendar case first - time only
        if ($this->noCalendar) {
            $options['noCalendar'] = true;
            $options['enableTime'] = true;
            $options['time_24hr'] = $this->time24hr;

            if ($this->format) {
                $options['dateFormat'] = $this->format;
            } else {
                $options['dateFormat'] = $this->timeFormat ?: 'H:i';
            }

            if ($this->timeFormat) {
                $options['time_24hr'] = ! str_contains($this->timeFormat, 'h');
            }
        } elseif ($this->format) {
            $options['dateFormat'] = $this->format;
        } else {
            $options['dateFormat'] = match ($this->type) {
                'datetime' => 'Y-m-d H:i',
                'time' => 'H:i',
                'range' => 'Y-m-d',
                'multiple' => 'Y-m-d',
                default => 'Y-m-d',
            };
        }

        if ($this->type === 'datetime' || $this->type === 'time' || $this->enableTime) {
            if (! $this->noCalendar) {
                $options['enableTime'] = true;
                $options['time_24hr'] = $this->time24hr;

                if ($this->timeFormat) {
                    $options['time_24hr'] = ! str_contains($this->timeFormat, 'h');
                }
            }
        }

        if ($this->minDate) {
            $options['minDate'] = $this->minDate;
        }
        if ($this->maxDate) {
            $options['maxDate'] = $this->maxDate;
        }

        if ($this->defaultDate) {
            $options['defaultDate'] = $this->defaultDate;
        } elseif ($this->value !== null && $this->value !== '') {
            $options['defaultDate'] = $this->value;
        }

        if ($this->locale) {
            $options['locale'] = $this->locale;
        }

        if ($this->altInput) {
            $options['altInput'] = true;
            if ($this->altFormat) {
                $options['altFormat'] = $this->altFormat;
            }
        }

        // Handle readonly state - disable Flatpickr interaction
        if ($this->readonly) {
            $options['allowInput'] = false;
            $options['clickOpens'] = false;
        } else {
            $options['allowInput'] = $this->allowInput;
            $options['clickOpens'] = $this->clickOpens;
        }
        $options['inline'] = $this->inline;
        $options['static'] = $this->static;
        $options['weekNumbers'] = $this->weekNumbers;
        $options['disableMobile'] = $this->disableMobile;

        if ($this->enableSeconds) {
            $options['enableSeconds'] = true;
        }

        if ($this->hourIncrement !== null) {
            $options['hourIncrement'] = $this->hourIncrement;
        }

        if ($this->minuteIncrement !== null) {
            $options['minuteIncrement'] = $this->minuteIncrement;
        }

        if ($this->defaultHour !== null) {
            $options['defaultHour'] = $this->defaultHour;
        }

        if ($this->defaultMinute !== null) {
            $options['defaultMinute'] = $this->defaultMinute;
        }

        if ($this->maxTime) {
            $options['maxTime'] = $this->maxTime;
        }

        if ($this->minTime) {
            $options['minTime'] = $this->minTime;
        }

        if ($this->disable !== null) {
            $options['disable'] = $this->disable;
        }

        if ($this->enable !== null) {
            $options['enable'] = $this->enable;
        }

        if ($this->position) {
            $options['position'] = $this->position;
        }

        $this->flatpickrOptions = array_merge($options, $this->options);

        if ($this->type === 'range') {
            $this->flatpickrOptions['mode'] = 'range';
        } elseif ($this->type === 'multiple') {
            $this->flatpickrOptions['mode'] = 'multiple';
        }
    }

    public function render(): View|Closure|string
    {
        return view('components.ui.date-picker');
    }
}