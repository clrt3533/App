<footer>
    @guest()
        <script>
			window.localStorage.removeItem('permissions');
        </script>
    @endguest

    @auth()
        <script>
			window.localStorage.setItem('permissions', JSON.stringify(
                <?php echo json_encode(array_merge(
                        resolve(\App\Repositories\Core\Auth\UserRepository::class)->getPermissionsForFrontEnd(),
                        [
                            'is_app_admin' => auth()->user()->isAppAdmin(),
                        ]
                    )
                )
                ?>
			))
        </script>
    @endauth

    <script>
		window.settings = <?php echo json_encode($settings) ?>
    </script>

    @stack('before-scripts')
    <script>

		window.localStorage.setItem('app-language', '{!! app()->getLocale() ?? "en"; !!}');

		window.localStorage.setItem('app-languages',
			JSON.stringify(
                    {!! json_encode(include resource_path().DIRECTORY_SEPARATOR.'lang'.DIRECTORY_SEPARATOR.(session()->get('locale') ?: $settings['language'] ?: "en").DIRECTORY_SEPARATOR.'default.php') !!}
			)
		);

		window.appLanguage = window.localStorage.getItem('app-language');

		window.localStorage.setItem('base_url', '{!! request()->root() !!}');

    </script>
    {!! script(mix('js/manifest.js')) !!}
    {!! script(mix('js/vendor.js')) !!}
    {!! script(mix('js/core.js')) !!}
    {!! script('vendor/summernote/summernote-bs4.js') !!}



    @stack('after-scripts')
</footer>
