<template>
	<div class="upload-container">
		<el-button v-bind:style="{background:color,borderColor:color}" style="width:100%" icon="el-icon-upload" size="small" type="primary" @click="handerCkfinder()">
      		{{ title }}
    	</el-button>
	</div>
</template>

<script>

export default {
  	name: 'ckfinder',

  	props: {
  		color: {
  			type: String,
  			default: '#1890ff'
  		},
  		title: {
  			type: String,
  			default: 'Upload'
  		},
  	},

  	data() {
  		return {
  			getUrl: '',
  		}
  	},

  	methods: {
  		handerCkfinder(){
  			this.init()
  		},

  		init() {
			const _this = this
			
			var finder = new CKFinder();

			finder.selectActionFunction = function(fileUrl, data){
				_this.$emit('input', fileUrl)
			};

			finder.popup();

    	},
  	}
}

</script>

<style >

</style>