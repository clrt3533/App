@stack('before-scripts')

<script>
	window.localStorage.setItem('app-language', '{!! app()->getLocale() ?? "en"; !!}');
	
	window.localStorage.setItem('app-languages',
			JSON.stringify(
					{!! json_encode(include resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.(session()->get('locale') ?: config('settings.application.language') ?: "en").DIRECTORY_SEPARATOR.'default.php') !!}
			)
	);

    window.appLanguage = window.localStorage.getItem('app-language');
    window.localStorage.setItem('base_url', '{!! request()->root() !!}');

</script>
{!! script(mix('js/manifest.js')) !!}
{!! script(mix('js/vendor.js')) !!}
{!! script(mix('js/core.js')) !!}
@stack('after-scripts')
