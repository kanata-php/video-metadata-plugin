
# Video Metadata

> Kanata Plugin

This plugin allows video metadata management.

## Installation

Activate plugin:

```shell
php kanata plugin:activate VideoMetadata
```


## Config

### Install ffmpeg

For this plugin, you need to install `ffmpeg` on your system.


## Commands

### Get Metadata

```shell
php kanata vm:get-metadata --video=/full/path/to/video
```

> This command uses FFMPeg. Go here for reference: https://ffmpeg.org/documentation.html

### Update Metadata

```shell
php kanata vm:update-metadata --video=/full/path/to/video --field="title" --value="John Doe\'s Vacation"
```

> This command uses AtomicParsley. Go here for reference: https://helpmanual.io/help/AtomicParsley/

## TODO

- Tests