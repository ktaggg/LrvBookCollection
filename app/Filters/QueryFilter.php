<?php

    namespace App\Filters;

    use Illuminate\Database\Eloquent\Builder;
    use Illuminate\Http\Request;

    abstract class QueryFilter
    {
        /**
         * The Eloquent builder instance.
         *
         * @var \Illuminate\Database\Eloquent\Builder
         */
        protected Builder $builder;

        /**
         * The HTTP request instance.
         *
         * @var \Illuminate\Http\Request
         */
        protected $request;

        /**
         * Create a new QueryFilter instance.
         *
         * @param \Illuminate\Http\Request $request
         */
        public function __construct(Builder $builder)
        {
            $this->builder = $builder;
        }

        /**
         * Apply the filters to the given query builder.
         *
         * @param \Illuminate\Database\Eloquent\Builder $builder
         * @return \Illuminate\Database\Eloquent\Builder
         */
        public function apply(array $filters)
        {
            foreach ($filters as $filter => $value) {
                if (
                    method_exists($this, $filter) &&
                    $value !== null &&
                    $value !== '' &&
                    $value !== 'null'
                ) {
                    $this->$filter($value);
                }
            }
            return $this->builder;
        }

        protected function filter($arr) {
            foreach ($arr as $key => $value) {
                if (method_exists($this, $key)) {
                    $this->$key($value);
                }
            }

            return $this->builder;
        }


        /**
         * Get the filters from the request.
         *
         * @return array
         */
        protected function filters(): array
        {
            return $this->request->all();
        }

        /**
         * Get a specific filter value from the request.
         *
         * @param string $key
         * @param mixed $default
         * @return mixed
         */
        protected function request($key, $default = null)
        {
            return $this->request->input($key, $default);
        }
    }

?>
