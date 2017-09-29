export default function (data) {
    if(!data.ok)throw new EventException('接口错误');
  return {
      isSuccess:function () {
          return data.body.success==1?true:false;
      },
      errorCode:function () {
          return data.body.code;
      },
      error:function () {
          return data.body.msg;
      },
      data:function () {
          return data.body.results;
      }
  }
}