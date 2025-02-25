# Kirbytext Image Ratio

A simple Kirby CMS plugin that extends the standard image Kirbytext tag to allow for aspect ratio cropping.

## Features

- Adds a `ratio` parameter to the standard Kirby image tag
- Applies intelligent cropping to maintain the specified aspect ratio
- Respects image focus points for better cropping results
- Preserves all standard image tag functionality when ratio is not specified
- No additional CSS or JavaScript required

## Installation

### Manual Installation

1. Create a new folder `site/plugins/kirbytext-img-ratio`
2. Download or copy the `index.php` file into this folder

### Git Installation

```bash
git submodule add git@github.com:grovesdm/kirbytext-img-ratio.git src/kirby/site/plugins/kirbytext-img-ratio
```

## Usage

Simply add the `ratio` parameter to your image tag:

```
(image: photo.jpg ratio: 16/9)
```

The ratio should be specified as width/height. Common examples:

```
(image: landscape.jpg ratio: 16/9)    // Widescreen
(image: portrait.jpg ratio: 3/4)      // Portrait
(image: square.jpg ratio: 1/1)        // Square
(image: cinema.jpg ratio: 21/9)       // Ultrawide
```

## Works with existing image tag features

All standard image tag features continue to work:

```
(image: photo.jpg 
  ratio: 16/9 
  caption: This is a caption
  alt: Alternative text
  class: my-custom-class
  link: another-image.jpg
)
```

## Focus Points

For best results, set focus points on your images in the Kirby panel. The plugin will respect these focus points when cropping.

## How Cropping Works

The plugin intelligently decides how to crop based on the original image proportions:

- If the original image is wider than the target ratio, it will crop the width while maintaining height
- If the original image is taller than the target ratio, it will crop the height while maintaining width

This approach preserves as much of the original image as possible.

## Requirements

- Kirby 3.x or higher

## License

MIT License
