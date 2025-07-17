<template>
  <b-card no-body>
    <b-row>

      <b-col md="12" sm="12" v-for="line in timeline" :key="line.titulo">
        <b-card style="margin: 5%; background-color: slategrey;">
          <span v-html="line.titulo"></span><br>
          <i>{{ line.data_hora }}</i>
        </b-card>
      </b-col>
      <br>

    </b-row>
  </b-card>
</template>

<script>
import { eventType } from '@/components/shared/eventType'
import Vue from 'vue'

export default {
  name: 'Timeline',
  props: [
    'processo'
  ],
  created () {
  },
  watch: {
    'processo' () {
      this.load(this.processo)
    }
  },
  mounted () {
    // eventType.$on('app', (result) => {
    //   this.filtros = result
    // })
    this.load(this.processo)

  },
  data: function () {
    return {
      active: 1,
      timeline: []
    }
  },
  computed: {

  },
  methods: {
    load (processo) {
      Vue.http.get('api/processo/' + processo + '/timeline').then(response => {
        this.timeline = response.body.data
      })
    }
  }
}
</script>
