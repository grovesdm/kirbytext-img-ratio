<?php
// site/plugins/kirbytext-img-ratio/index.php

Kirby::plugin('grovesdm/kirbytext-img-ratio', [
    'tags' => [
        'image' => [
            'attr' => [
                'alt',
                'caption',
                'class',
                'height',
                'link',
                'linkclass',
                'rel',
                'target',
                'title',
                'width',
                'ratio'  // Our custom attribute
            ],
            'html' => function($tag) {
                // Get the image file
                $file = $tag->file($tag->value);

                // If no file found, return empty string
                if (!$file) {
                    return '';
                }

                // Check if ratio parameter exists
                $ratio = $tag->ratio ?? null;

                // If no ratio specified, use Kirby's default tag behavior
                if (!$ratio) {
                    return kirby()->kirbytext()->defaultTags()['image']['html']($tag);
                }

                try {
                    // Parse the ratio (e.g., "16/9", "1/1", etc.)
                    if (strpos($ratio, '/') !== false) {
                        list($width, $height) = explode('/', $ratio);
                        $ratioValue = (float)$width / (float)$height;
                    } else {
                        // Fallback if ratio is incorrectly formatted
                        return kirby()->kirbytext()->defaultTags()['image']['html']($tag);
                    }

                    // Get focus point if available
                    $focus = $file->focus()->isNotEmpty() ? $file->focus()->value() : 'center';

                    // Calculate dimensions while maintaining the ratio
                    $originalWidth = $file->width();
                    $originalHeight = $file->height();
                    $originalRatio = $originalWidth / $originalHeight;

                    // Determine how to crop based on original vs target ratio
                    if ($ratioValue > $originalRatio) {
                        // Width constrained crop (crop height)
                        $cropWidth = $originalWidth;
                        $cropHeight = round($originalWidth / $ratioValue);
                    } else {
                        // Height constrained crop (crop width)
                        $cropHeight = $originalHeight;
                        $cropWidth = round($originalHeight * $ratioValue);
                    }

                    // Create the cropped version
                    $thumb = $file->crop($cropWidth, $cropHeight, [
                        'quality' => 90,
                        'focus' => $focus
                    ]);

                    // Build the HTML
                    $alt = $tag->alt ?? $file->alt()->or($file->name())->value();
                    $class = $tag->class ?? '';

                    // Create the image tag
                    $img = '<img src="' . $thumb->url() . '" alt="' . $alt . '" ';
                    $img .= 'width="' . $thumb->width() . '" height="' . $thumb->height() . '" ';
                    if (!empty($class)) {
                        $img .= 'class="' . $class . '" ';
                    }
                    $img .= 'loading="lazy">';

                    // Add figure tags and caption if provided
                    if ($tag->caption) {
                        $figure = '<figure>';
                        $figure .= $img;
                        $figure .= '<figcaption>' . $tag->caption . '</figcaption>';
                        $figure .= '</figure>';
                        $img = $figure;
                    }

                    // Add link if specified
                    if ($tag->link) {
                        $link = $tag->file($tag->link) ? $tag->file($tag->link)->url() : $tag->link;
                        $linkclass = $tag->linkclass ?? '';
                        $rel = $tag->rel ? ' rel="' . $tag->rel . '"' : '';
                        $target = $tag->target ? ' target="' . $tag->target . '"' : '';
                        $title = $tag->title ? ' title="' . $tag->title . '"' : '';

                        $img = '<a href="' . $link . '"' .
                            (!empty($linkclass) ? ' class="' . $linkclass . '"' : '') .
                            $rel . $target . $title . '>' . $img . '</a>';
                    }

                    return $img;

                } catch (Exception $e) {
                    // If something goes wrong, fall back to the default tag
                    return kirby()->kirbytext()->defaultTags()['image']['html']($tag);
                }
            }
        ]
    ]
]);
