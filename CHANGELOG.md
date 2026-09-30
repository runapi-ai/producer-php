# Changelog

## [v0.3.0](https://github.com/runapi-ai/producer-php/releases/tag/v0.3.0) - 2026-09-30

### Changed
- Send request parameters to the service without local validation. Model ids and parameter values the service supports work without an SDK upgrade; static types and enum constants remain for completion.
  Migration: Invalid parameters now throw `ValidationException` built from the service's 400 response, including its status and message, instead of a `ValidationException` thrown locally before the request.


## [v0.2.0](https://github.com/runapi-ai/producer-php/releases/tag/v0.2.0) - 2026-07-23

### Added
- Add typed model selection for seven additional Producer FUZZ music generation versions.


## [v0.1.0](https://github.com/runapi-ai/producer-php/releases/tag/v0.1.0) - 2026-07-20

### Added
- Add the Producer Composer package for FUZZ exact-lyrics and instrumental music generation.
- Include typed audio metadata and task generation stages.
