
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

### Update Metadata

> TODO

```shell
php kanata vm:update-metadata --video=/full/path/to/video --metadata=title:"John Doe\'s Vacation",year:2022
```

## TODO

- Update Metadata Command
- Tests